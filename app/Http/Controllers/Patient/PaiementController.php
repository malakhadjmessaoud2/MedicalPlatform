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
            'return_url'  => "https://d0007b64457b.ngrok.io/api/payment/success",
            'cancel_url'  => "https://d0007b64457b.ngrok.io/api/payment/cancel/{$rendezVous->id}",
            'webhook_url' => "https://d0007b64457b.ngrok.io/api/webhook/paymee",
        ];

        Log::info('Création du paiement Paymee', $paymentData);

        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $PAYMEE_API_TOKEN,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://sandbox.paymee.tn/api/v2/payments/create', $paymentData);   //dev test

        Log::info('Réponse Paymee create', [
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);

        if ($response->successful()) {
            $responseData = $response->json();

            if (isset($responseData['data']['token'])) {
                $token = $responseData['data']['token'];

                $rendezVous->update(['payment_token' => $token]);

                $paymeeUrl = 'https://app.paymee.tn/gateway/' . $token; // prod
                Log::info("Redirection vers Paymee URL: {$paymeeUrl}");

                return view('payment.redirect', compact('rendezVous', 'token', 'paymeeUrl'));
            } else {
                Log::error('Réponse Paymee invalide : token manquant', (array) $responseData);
                return redirect()->back()->with('error', 'Impossible de générer le paiement (token manquant)');
            }
        } else {
            Log::error('Payment Error Response', ['response' => $response->json()]);
            return redirect()->back()->with('error', 'Erreur de création du paiement');
        }
    }

    public function success(Request $request)
    {
        $PAYMEE_API_TOKEN = '3db90865c567a43840ecefa9815d76e2b36f134b';

        $paymentToken = $request->query('payment_token');
        $paymentId    = $request->query('payment_id');

        Log::info('Callback success reçu', [
            'payment_token' => $paymentToken,
            'payment_id'    => $paymentId,
        ]);

        if (!$paymentToken || !$paymentId) {
            Log::error('Paramètres manquants dans callback success');
            return response()->json(['error' => 'Missing parameters'], 400);
        }

        $rendezvous = RendezVous::where('payment_token', $paymentToken)->first();

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

            $rendezvous->update(['status' => 'payed']);

            Log::info("Paiement enregistré avec succès pour rendez-vous #{$rendezvous->id}");

            return view('dashPatient.RendezVous.success', compact('rendezvous'));
        } else {
            $paiement = new Paiement();
            $paiement->paiement_id   = $paymentId;
            $paiement->status        = 'cancel';
            $paiement->amount        = 0;
            $paiement->currency      = 'TND';
            $paiement->rendezvous_id = $rendezvous->id;
            $paiement->datePaiement  = now();
            $paiement->save();

            $rendezvous->update(['status' => 'canceled']);

            Log::warning("Paiement annulé pour rendez-vous #{$rendezvous->id}");

            return view('dashPatient.RendezVous.cancel', compact('rendezvous'));
        }
    }

    public function cancel($rendezvousId)
    {
        $rendezvous = RendezVous::findOrFail($rendezvousId);
        $rendezvous->update(['status' => 'canceled']);

        Log::warning("Paiement annulé manuellement pour rendez-vous #{$rendezvous->id}");

        return view('payment.cancel', compact('rendezvous'));
    }

    public function handleWebhook(Request $request)
    {
        Log::info('Webhook Paymee reçu', $request->all());

        return response()->json(['message' => 'Transaction enregistrée avec succès'], 200);
    }
}
