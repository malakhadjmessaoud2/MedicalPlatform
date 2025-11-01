<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaiementController extends Controller
{
    public function create(RendezVous $rendezVous)
    {
        $rendezVous->load('medecin');
        $user = Auth::user();
        $PAYMEE_API_TOKEN = '3db90865c567a43840ecefa9815d76e2b36f134b';

        $paymentData = [
            'amount'      => $rendezVous->medecin->prixConsultation,
            'note'        => "Rendez-vous #" . $rendezVous->id,
            'first_name'  => $user->nom,
            'last_name'   => $user->prenom,
            'email'       => $user->email,
            'phone'       => $user->tel,
            'return_url'  => "https://2980d62ac769.ngrok-free.app/payment/success",
            'cancel_url'  => "https://2980d62ac769.ngrok-free.app/payment/cancel/{$rendezVous->id}",
            'webhook_url' => "https://2980d62ac769.ngrok-free.app/webhook/paymee",
        ];

        Log::info('Création du paiement Paymee', $paymentData);

        $isSandbox = true; // false en production

        $baseApiUrl  = $isSandbox ? 'https://sandbox.paymee.tn/api/v2' : 'https://app.paymee.tn/api/v2';
        $gatewayUrl  = $isSandbox ? 'https://sandbox.paymee.tn/gateway/' : 'https://app.paymee.tn/gateway/';

        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $PAYMEE_API_TOKEN,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])->post($baseApiUrl . '/payments/create', $paymentData);

        if ($response->successful()) {
            $token = $response->json()['data']['token'];
            $paymeeUrl = $gatewayUrl . $token;
            $rendezVous->update(['payment_token' => $token]);

            return view('payment.redirect', compact('rendezVous', 'token', 'paymeeUrl'));
        } else {
            Log::error('Erreur lors de la création du paiement Paymee', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return redirect()->back()->with('error', 'Erreur lors de la création du paiement. Veuillez réessayer.');
        }
    }

    public function success(Request $request)
    {
        $PAYMEE_API_TOKEN = '3db90865c567a43840ecefa9815d76e2b36f134b';

        $paymentToken = $request->query('payment_token');
        $paymentId    = $request->query('payment_id') ?? $request->query('transaction');

        Log::info('Callback success reçu', [
            'payment_token' => $paymentToken,
            'payment_id'    => $paymentId,
        ]);

        if (!$paymentToken || !$paymentId) {
            Log::error('Paramètres manquants dans callback success');
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $rendezvous = RendezVous::where('payment_token', $paymentToken)->with('patient')->first();

        if (!$rendezvous) {
            Log::error("Rendez-vous non trouvé pour token {$paymentToken}");
            return response()->json(['error' => 'Rendez-vous not found'], 404);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $PAYMEE_API_TOKEN,
            'Accept'        => 'application/json',
        ])->get("https://sandbox.paymee.tn/api/v1/payments/{$rendezvous->payment_token}/check");

        Log::info('Réponse Paymee check', [
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);

        if (!$response->successful()) {
            return redirect()->back()->with('error', 'Erreur lors de la vérification du paiement');
        }

        $responseData = $response->json();

        if (!empty($responseData['data']['payment_status']) && $responseData['data']['payment_status'] === true) {
            $paiement = new Paiement();
            $paiement->paiement_id   = $paymentId;
            $paiement->status        = 'success';
            $paiement->amount        = $responseData['data']['amount'] ?? 0;
            $paiement->currency      = $responseData['data']['currency'] ?? 'TND';
            $paiement->details       = $responseData['data']['details'] ?? null;
            $paiement->rendezvous_id = $rendezvous->id;
            $paiement->datePaiement  = now();
            $paiement->save();

            // Transition autorisée: confirmed -> payed
            if ($rendezvous->statut !== 'confirmed') {
                Log::warning("Paiement reçu mais statut actuel invalide pour payer", ['rdv_id' => $rendezvous->id, 'statut' => $rendezvous->statut]);
                return redirect()->back()->with('error', "Le rendez-vous n'est pas dans un état payable.");
            }
            $old = $rendezvous->statut;
            $rendezvous->transitionTo('payed');
            // Notifier le médecin du paiement
            try {
                $medecin = $rendezvous->medecin;
                if ($medecin) {
                    $medecin->notify(new \App\Notifications\RendezVousStatusChangedNotification($rendezvous, $old, 'payed'));
                }
            } catch (\Throwable $e) {
                Log::warning('Notification paiement médecin échouée', ['error' => $e->getMessage()]);
            }

            Log::info("Paiement enregistré avec succès pour rendez-vous #{$rendezvous->id}");

            $user = $rendezvous->patient;
            session(['impersonate_user_id' => $user->id]);

            return view('payment.success', compact('rendezvous'));
        } else {
            $paiement = new Paiement();
            $paiement->paiement_id   = $paymentId;
            $paiement->status        = 'cancel';
            $paiement->amount        = 0;
            $paiement->currency      = 'TND';
            $paiement->rendezvous_id = $rendezvous->id;
            $paiement->datePaiement  = now();
            $paiement->save();

            // Aucun changement de statut ici si le paiement échoue; l'utilisateur peut réessayer

            Log::warning("Paiement annulé pour rendez-vous #{$rendezvous->id}");

            $user = $rendezvous->user;
            session(['impersonate_user_id' => $user->id]);

            return view('payment.cancel', compact('rendezvous'));
        }
    }

    public function cancel($rendezvousId)
    {
        $rendezvous = RendezVous::with('patient')->findOrFail($rendezvousId);
        // Patient peut annuler si pending ou confirmed
            if (in_array($rendezvous->statut, ['pending','confirmed'], true)) {
                $old = $rendezvous->statut;
                $rendezvous->transitionTo('cancelled');
                // Notifier le médecin de l'annulation
                try {
                    $medecin = $rendezvous->medecin;
                    if ($medecin) {
                        $medecin->notify(new \App\Notifications\RendezVousStatusChangedNotification($rendezvous, $old, 'cancelled'));
                    }
                } catch (\Throwable $e) {
                    Log::warning('Notification annulation médecin échouée', ['error' => $e->getMessage()]);
                }
            }

        Log::warning("Paiement annulé manuellement pour rendez-vous #{$rendezvous->id}");

        $user = $rendezvous->patient;
        session(['impersonate_user_id' => $user->id]);

        return view('payment.cancel', compact('rendezvous'));
    }

    public function handleWebhook(Request $request)
    {
        Log::info('Webhook Paymee reçu', $request->all());

        return response()->json(['message' => 'Transaction enregistrée avec succès'], 200);
    }
}
