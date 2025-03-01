<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Events\RendezVousModifie;
use App\Models\Medecin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Patient;

class RendezVousController extends Controller
{
    /**
     * Affiche la page de l'agenda
     */
    public function index()
    {
        return view('dashMedecin.AgendaRendezvous.index');
    }

    /**
     * Récupère les événements pour le calendrier
     */
    public function getEvenements(Request $request)
    {
        Log::info('getEvenements called', [
            'request' => $request->all(),
            'url' => $request->fullUrl(),
            // 'user' => patient()
        ]);

        try {
            $debut = $request->start;
            $fin = $request->end;

            $rendezVous = RendezVous::whereBetween('date_debut', [$debut, $fin])
                ->with('patient:id,nom')
                ->get()
                ->map(function($rdv) {
                    return [
                        'id' => $rdv->id,
                        'title' => $rdv->patient->nom,
                        'start' => $rdv->date_debut->toIso8601String(),
                        'end' => $rdv->date_fin->toIso8601String(),
                        'extendedProps' => [
                            'type' => $rdv->type,
                            'statut' => $rdv->statut,
                            'description' => $rdv->description,
                        ]
                    ];
                });

            Log::info('Rendez-vous récupérés', ['count' => $rendezVous->count()]);
            return response()->json($rendezVous);
        } catch (\Exception $e) {
            Log::error('Erreur dans getEvenements', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Enregistre un nouveau rendez-vous
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'type' => 'required|in:consultation,suivi,urgence',
            'description' => 'nullable|string',
            'titre' => 'required|string',
            'statut' => 'required|in:confirmé,en_attente,annulé'
        ]);

        try {
            $rendezVous = RendezVous::create([
                'medecin_id' => 1, // Utilisez l'ID du médecin connecté
                ...$validated
            ]);

            // Charger la relation patient pour l'événement
            $rendezVous->load('patient:id,nom,prenom');

            // Diffuser l'événement
            broadcast(new RendezVousModifie($rendezVous, 'created'))->toOthers();

            return response()->json([
                'message' => 'Rendez-vous créé avec succès',
                'rendezVous' => $rendezVous
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du rendez-vous: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la création du rendez-vous',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche les détails d'un rendez-vous
     */
    public function show(RendezVous $rendezVous)
    {
        return response()->json($rendezVous->load('patient:id,nom'));
    }

    /**
     * Met à jour un rendez-vous
     */
    public function update(Request $request, RendezVous $rendezVous)
    {
        try {
            // Si c'est un drag & drop ou resize, on valide différemment
            if ($request->has('start') && $request->has('end')) {
                $validated = $request->validate([
                    'start' => 'required|date',
                    'end' => 'required|date|after:start'
                ]);

                $rendezVous->update([
                    'date_debut' => $validated['start'],
                    'date_fin' => $validated['end']
                ]);
            } else {
                // Validation normale pour la mise à jour complète
                $validated = $request->validate([
                    'patient_id' => 'required|exists:patients,id',
                    'date_debut' => 'required|date',
                    'date_fin' => 'required|date|after:date_debut',
                    'type' => 'required|in:consultation,suivi,urgence',
                    'description' => 'nullable|string',
                    'titre' => 'required|string',
                    'statut' => 'required|in:confirmé,en_attente,annulé'
                ]);

                $rendezVous->update($validated);
            }

            // Charger la relation patient pour l'événement
            $rendezVous->load('patient:id,nom,prenom');

            // Diffuser l'événement
            broadcast(new RendezVousModifie($rendezVous, 'updated'))->toOthers();

            return response()->json([
                'message' => 'Rendez-vous mis à jour avec succès',
                'rendezVous' => $rendezVous
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du rendez-vous: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du rendez-vous',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprime un rendez-vous
     */
    public function destroy(RendezVous $rendezVous)
    {
        try {
            // Vérifier si le rendez-vous existe
            if (!$rendezVous) {
                return response()->json([
                    'message' => 'Rendez-vous non trouvé'
                ], 404);
            }

            // Supprimer définitivement le rendez-vous (force delete)
            $rendezVous->forceDelete();  // Au lieu de delete()

            // Notifier les autres utilisateurs via WebSocket si nécessaire
            broadcast(new RendezVousModifie($rendezVous, 'deleted'))->toOthers();

            return response()->json([
                'message' => 'Rendez-vous supprimé avec succès',
                'id' => $rendezVous->id
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du rendez-vous: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la suppression du rendez-vous',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupère la liste des patients pour le select
     */
    public function getPatients(Request $request)
    {
        $search = $request->query('search', '');

        $patients = Patient::where('nom', 'LIKE', "%{$search}%")
            ->orWhere('prenom', 'LIKE', "%{$search}%")
            ->select('id', 'nom', 'prenom')
            ->limit(10)
            ->get()
            ->map(function($patient) {
                return [
                    'id' => $patient->id,
                    'text' => $patient->nom . ' ' . $patient->prenom
                ];
            });

        return response()->json($patients);
    }
}
