<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\DossierMedical;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DossierController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user || !$user->isPatient()) {
            abort(403, 'Accès non autorisé. Vous devez être un patient.');
        }
        $patientId = $user->id;
        Log::info('DossierController@index start', ['patient_id' => $patientId, 'medecin_id_param' => $request->get('medecin_id')]);

        // Vérifier si c'est une demande de téléchargement PDF
        if ($request->get('download') === 'true') {
            return $this->generateDossierPDF($request, $patientId);
        }

        // Dossier du patient
        $dossier = DossierMedical::where('patient_id', $patientId)->first();
        if (!$dossier) {
            Log::error('DossierController@index: aucun dossier médical pour le patient', ['patient_id' => $patientId]);
            $medecins = collect();
        } else {
            Log::info('DossierController@index: dossier trouvé', ['dossier_id' => $dossier->id]);
            // Extraire les rendezvous_id des consultations liées à ce dossier
            $rvIdsWithConsult = Consultation::where('dossier_medical_id', $dossier->id)
                ->whereNotNull('rendezvous_id')
                ->pluck('rendezvous_id');
            Log::error('DossierController@index rvIdsWithConsult', ['count' => $rvIdsWithConsult->count(), 'sample' => $rvIdsWithConsult->take(10)->values()->all()]);

            // Cas principal: médecins ayant des consultations liées à un rendez-vous
            $medecinIds = RendezVous::where('patient_id', $patientId)
                ->whereIn('id', $rvIdsWithConsult)
                ->distinct()
                ->pluck('medecin_id');

            // Fallback: s'il n'y a pas (encore) de consultations liées à un rendez-vous,
            // afficher les médecins avec lesquels le patient a au moins un rendez-vous (quel que soit le statut)
            if ($medecinIds->isEmpty()) {
                $medecinIds = RendezVous::where('patient_id', $patientId)
                    ->distinct()
                    ->pluck('medecin_id');
                Log::info('DossierController@index fallback medecinIds from rendezvous only', ['count' => $medecinIds->count(), 'ids' => $medecinIds->values()->all()]);
            } else {
                Log::error('DossierController@index medecinIds', ['count' => $medecinIds->count(), 'ids' => $medecinIds->values()->all()]);
            }

            $medecins = User::whereIn('id', $medecinIds)->get();
            Log::error('DossierController@index medecins count', ['count' => $medecins->count()]);
        }

        $selectedMedecinId = $request->get('medecin_id');
        $consultations = collect();
        if ($selectedMedecinId) {
            // Récupérer d'abord les IDs des rendez-vous avec ce médecin
            $rendezVousIds = RendezVous::where('patient_id', $patientId)
                ->where('medecin_id', (int) $selectedMedecinId)
                ->pluck('id');

            // Puis récupérer les consultations liées à ces rendez-vous
            $consultations = Consultation::with(['rendezVous.medecin', 'ordonnances'])
                ->whereIn('rendezvous_id', $rendezVousIds)
                ->orderBy('date', 'desc')
                ->get();
            Log::info('DossierController@index consultations for selected medecin', [
                'selected_medecin_id' => $selectedMedecinId,
                'rendez_vous_ids' => $rendezVousIds->toArray(),
                'consultations_count' => $consultations->count(),
                'consultations_data' => $consultations->map(function($c) {
                    return [
                        'id' => $c->id,
                        'rendezvous_id' => $c->rendezvous_id,
                        'date' => $c->date,
                        'has_ordonnance' => $c->ordonnances ? true : false
                    ];
                })->toArray()
            ]);

            if ($request->ajax()) {
                // Récupérer tous les rendez-vous avec ce médecin (quel que soit le statut)
                $rendezVous = RendezVous::where('patient_id', $patientId)
                    ->where('medecin_id', (int) $selectedMedecinId)
                    ->orderBy('date_debut', 'desc')
                    ->get();

                // Organiser les données par rendez-vous
                $rendezVousAvecConsultations = $rendezVous->map(function ($rv) use ($consultations) {
                    // Trouver les consultations liées à ce rendez-vous
                    $consultationsDuRV = $consultations->where('rendezvous_id', $rv->id);
                    Log::info('DossierController@index mapping rendez-vous', [
                        'rendez_vous_id' => $rv->id,
                        'consultations_count' => $consultationsDuRV->count(),
                        'consultations_ids' => $consultationsDuRV->pluck('id')->toArray()
                    ]);

                    return [
                        'rendez_vous' => [
                            'id' => $rv->id,
                            'date_debut' => $rv->date_debut ? $rv->date_debut->format('Y-m-d H:i:s') : null,
                            'date_debut_formatted' => $rv->date_debut ? $rv->date_debut->format('d/m/Y à H:i') : null,
                            'date_fin' => $rv->date_fin ? $rv->date_fin->format('Y-m-d H:i:s') : null,
                            'date_fin_formatted' => $rv->date_fin ? $rv->date_fin->format('d/m/Y à H:i') : null,
                            'statut' => $rv->statut,
                            'type' => $rv->type,
                            'description' => $rv->description,
                        ],
                        'consultations' => $consultationsDuRV->map(function ($c) {
                            $ord = $c->ordonnances;



                            // Vérifier si l'ordonnance a un fichier
                            $hasOrdonnanceFile = $ord && $ord->file && !empty($ord->file);

                            return [
                                'id' => $c->id,
                                'date' => optional($c->date)->format('Y-m-d H:i:s'),
                                'date_formatted' => optional($c->date)->format('d/m/Y'),
                                'type' => $c->type,
                                'motif' => $c->motif,
                                'symptomes' => $c->symptomes,
                                'tension_arterielle' => $c->tension_arterielle,
                                'frequence_cardiaque' => $c->frequence_cardiaque,
                                'temperature' => $c->temperature,
                                'saturation_o2' => $c->saturation_o2,
                                'poids' => $c->poids,
                                'taille' => $c->taille,
                                'imc' => $c->imc,
                                'traitement_actuel' => $c->traitement_actuel,
                                'medicaments_prescrits' => $c->medicaments_prescrits,
                                'propositions_suivi' => $c->propositions_suivi,
                                'instructions_particulieres' => $c->instructions_particulieres,
                                'evolution_symptomes' => $c->evolution_symptomes,
                                'effets_secondaires' => $c->effets_secondaires,
                                'examens_controle' => $c->examens_controle,
                                'orientation_patient' => $c->orientation_patient,
                                'ordonnance' => $hasOrdonnanceFile ? [
                                    'id' => $ord->id,
                                    'medicaments' => $ord->medicaments,
                                    'notes' => $ord->notes,
                                    'file' => $ord->file,
                                    'file_url' => asset('storage/'.$ord->file),
                                ] : null,
                            ];
                        })->values(),
                    ];
                });

                return response()->json([
                    'success' => true,
                    'rendez_vous_avec_consultations' => $rendezVousAvecConsultations,
                ]);
            }
        }

        return view('dashPatient.dossier.index', compact('medecins', 'consultations', 'selectedMedecinId'));
    }

    /**
     * Génère un PDF du dossier médical complet pour un médecin spécifique
     */
    private function generateDossierPDF(Request $request, $patientId)
    {
        try {
            $medecinId = $request->get('medecin_id');
            if (!$medecinId) {
                return response()->json(['error' => 'ID du médecin requis'], 400);
            }

            // Récupérer les informations du médecin
            $medecin = User::find($medecinId);
            if (!$medecin) {
                return response()->json(['error' => 'Médecin non trouvé'], 404);
            }

            // Récupérer les informations du patient
            $patient = User::find($patientId);
            if (!$patient) {
                return response()->json(['error' => 'Patient non trouvé'], 404);
            }

            // Récupérer tous les rendez-vous avec ce médecin (quel que soit le statut)
            $rendezVous = RendezVous::where('patient_id', $patientId)
                ->where('medecin_id', (int) $medecinId)
                ->orderBy('date_debut', 'desc')
                ->get();

            // Récupérer les consultations
            $rendezVousIds = $rendezVous->pluck('id');
            $consultations = Consultation::with(['ordonnances'])
                ->whereIn('rendezvous_id', $rendezVousIds)
                ->orderBy('date', 'desc')
                ->get();

            // Log pour debug des consultations et ordonnances
            Log::info('Debug consultations et ordonnances', [
                'rendez_vous_ids' => $rendezVousIds->toArray(),
                'consultations_count' => $consultations->count(),
                'consultations_with_ordonnances' => $consultations->filter(function($c) {
                    return $c->ordonnances && $c->ordonnances->file && !empty($c->ordonnances->file);
                })->count(),
                'consultations_details' => $consultations->map(function($c) {
                    return [
                        'id' => $c->id,
                        'has_ordonnance' => $c->ordonnances ? 'yes' : 'no',
                        'ordonnance_file' => $c->ordonnances ? $c->ordonnances->file : 'null',
                        'ordonnance_id' => $c->ordonnances ? $c->ordonnances->id : 'null'
                    ];
                })->toArray()
            ]);

            // Organiser les données pour le PDF
            $dossierData = [
                'patient' => [
                    'nom' => $patient->nom,
                    'prenom' => $patient->prenom,
                    'email' => $patient->email,
                    'date_naissance' => $patient->date_naissance ? $patient->date_naissance->format('d/m/Y') : 'Non spécifiée',
                ],
                'medecin' => [
                    'nom' => $medecin->nom,
                    'prenom' => $medecin->prenom,
                    'specialite' => $medecin->specialite ?? 'Généraliste',
                ],
                'statistiques' => [
                    'total_rendez_vous' => $rendezVous->count(),
                    'consultations_effectuees' => $consultations->count(),
                    'ordonnances_disponibles' => $consultations->whereNotNull('ordonnances')->count(),
                ],
                'rendez_vous' => $rendezVous->map(function ($rv) use ($consultations) {
                    $consultationsDuRV = $consultations->where('rendezvous_id', $rv->id);
                    return [
                        'date_debut' => $rv->date_debut ? $rv->date_debut->format('Y-m-d H:i:s') : null,
                        'date_debut_formatted' => $rv->date_debut ? $rv->date_debut->format('d/m/Y à H:i') : null,
                        'date_fin' => $rv->date_fin ? $rv->date_fin->format('Y-m-d H:i:s') : null,
                        'date_fin_formatted' => $rv->date_fin ? $rv->date_fin->format('d/m/Y à H:i') : null,
                        'statut' => $rv->statut,
                        'type' => $rv->type,
                        'description' => $rv->description,
                        'consultations' => $consultationsDuRV->map(function ($c) {
                            $ord = $c->ordonnances;

                            // Vérifier si l'ordonnance a un fichier
                            $hasOrdonnanceFile = $ord && $ord->file && !empty($ord->file);

                            return [
                                'date' => $c->date ? $c->date->format('Y-m-d H:i:s') : null,
                                'date_formatted' => $c->date ? $c->date->format('d/m/Y') : null,
                                'motif' => $c->motif,
                                'symptomes' => $c->symptomes,
                                'tension_arterielle' => $c->tension_arterielle,
                                'frequence_cardiaque' => $c->frequence_cardiaque,
                                'temperature' => $c->temperature,
                                'saturation_o2' => $c->saturation_o2,
                                'poids' => $c->poids,
                                'taille' => $c->taille,
                                'imc' => $c->imc,
                                'traitement_actuel' => $c->traitement_actuel,
                                'medicaments_prescrits' => $c->medicaments_prescrits,
                                'propositions_suivi' => $c->propositions_suivi,
                                'instructions_particulieres' => $c->instructions_particulieres,
                                'evolution_symptomes' => $c->evolution_symptomes,
                                'effets_secondaires' => $c->effets_secondaires,
                                'examens_controle' => $c->examens_controle,
                                'orientation_patient' => $c->orientation_patient,
                                'ordonnance' => $hasOrdonnanceFile ? [
                                    'medicaments' => $ord->medicaments,
                                    'notes' => $ord->notes,
                                    'file' => $ord->file,
                                    'file_path' => storage_path('app/public/' . $ord->file),
                                    'file_url' => url('storage/' . $ord->file),
                                    'file_exists' => file_exists(storage_path('app/public/' . $ord->file)),
                                    'file_base64' => file_exists(storage_path('app/public/' . $ord->file)) ?
                                        $this->resizeImageForPDF(storage_path('app/public/' . $ord->file)) : null,
                                ] : null,
                            ];
                        })->values(),
                    ];
                }),
            ];

            // Log pour debug
            Log::info('Données du dossier préparées pour PDF', [
                'patient_id' => $patientId,
                'medecin_id' => $medecinId,
                'rendez_vous_count' => $rendezVous->count(),
                'consultations_count' => $consultations->count(),
                'dossier_data_keys' => array_keys($dossierData)
            ]);

            // Log détaillé des ordonnances pour debug
            $ordonnancesDebug = [];
            foreach ($dossierData['rendez_vous'] as $rv) {
                foreach ($rv['consultations'] as $consultation) {
                    if ($consultation['ordonnance']) {
                        $ordonnancesDebug[] = [
                            'consultation_id' => $consultation['id'] ?? 'N/A',
                            'file' => $consultation['ordonnance']['file'],
                            'file_exists' => $consultation['ordonnance']['file_exists'],
                            'file_path' => $consultation['ordonnance']['file_path'],
                            'file_url' => $consultation['ordonnance']['file_url'],
                        ];
                    }
                }
            }
            Log::info('Debug des ordonnances', $ordonnancesDebug);

            // Vérification des données avant génération PDF
            if (empty($dossierData['rendez_vous'])) {
                Log::warning('Aucun rendez-vous trouvé pour la génération PDF', [
                    'patient_id' => $patientId,
                    'medecin_id' => $medecinId
                ]);
                return response()->json([
                    'error' => 'Aucun rendez-vous trouvé pour ce médecin',
                    'message' => 'Il n\'y a pas de données à inclure dans le dossier PDF.'
                ], 404);
            }

            // Générer le PDF
            $pdf = PDF::loadView('pdf.dossier-medical', $dossierData);

            // Configuration du PDF
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial',
                'chroot' => public_path(),
                'enable_remote' => true,
                'enable_php' => false,
            ]);

            // Nom du fichier
            $fileName = 'dossier_medical_' . str_replace(' ', '_', $medecin->prenom . '_' . $medecin->nom) . '_' . date('Y-m-d') . '.pdf';

            // Retourner le PDF en tant que téléchargement
            return $pdf->download($fileName);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du PDF du dossier médical', [
                'patient_id' => $patientId,
                'medecin_id' => $request->get('medecin_id'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Erreur lors de la génération du PDF',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Visualise une ordonnance dans le navigateur
     */
    public function viewOrdonnance(Request $request)
    {
        try {
            $filePath = $request->get('file');
            $patientId = Auth::id();

            if (!$filePath) {
                Log::warning('Tentative de visualisation sans chemin de fichier');
                return response()->json(['error' => 'Chemin du fichier requis'], 400);
            }

            // Vérifier que l'utilisateur a accès à cette ordonnance
            $ordonnance = \App\Models\Ordonnance::where('file', $filePath)
                ->whereHas('consultation.rendezVous', function($query) use ($patientId) {
                    $query->where('patient_id', $patientId);
                })
                ->first();

            if (!$ordonnance) {
                Log::error('Tentative d\'accès non autorisé à l\'ordonnance', [
                    'file_path' => $filePath,
                    'patient_id' => $patientId
                ]);
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }

            // Construire le chemin complet du fichier
            $fullPath = storage_path('app/public/' . $filePath);

            // Log pour debug
            Log::info('Tentative de visualisation d\'ordonnance', [
                'file_path' => $filePath,
                'full_path' => $fullPath,
                'file_exists' => file_exists($fullPath),
                'ordonnance_id' => $ordonnance->id
            ]);

            // Vérifier que le fichier existe
            if (!file_exists($fullPath)) {
                Log::error('Fichier d\'ordonnance non trouvé pour visualisation', [
                    'file_path' => $filePath,
                    'full_path' => $fullPath
                ]);
                return response()->json(['error' => 'Fichier non trouvé'], 404);
            }

            // Déterminer le type MIME du fichier
            $mimeType = mime_content_type($fullPath);
            if (!$mimeType) {
                $mimeType = 'application/octet-stream';
            }

            // Retourner le fichier pour visualisation
            return response()->file($fullPath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la visualisation de l\'ordonnance', [
                'file_path' => $request->get('file'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Erreur lors de la visualisation',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Télécharge une ordonnance dans son format original
     */
    public function downloadOrdonnance(Request $request)
    {
        try {
            $filePath = $request->get('file');
            $consultationDate = $request->get('consultation_date');
            $patientId = Auth::id();

            if (!$filePath) {
                Log::warning('Tentative de téléchargement sans chemin de fichier');
                return response()->json(['error' => 'Chemin du fichier requis'], 400);
            }

            // Vérifier que l'utilisateur a accès à cette ordonnance
            $ordonnance = \App\Models\Ordonnance::where('file', $filePath)
                ->whereHas('consultation.rendezVous', function($query) use ($patientId) {
                    $query->where('patient_id', $patientId);
                })
                ->first();

            if (!$ordonnance) {
                Log::error('Tentative d\'accès non autorisé à l\'ordonnance', [
                    'file_path' => $filePath,
                    'patient_id' => $patientId
                ]);
                return response()->json(['error' => 'Accès non autorisé'], 403);
            }

            // Construire le chemin complet du fichier
            $fullPath = storage_path('app/public/' . $filePath);

            // Log pour debug
            Log::info('Tentative de téléchargement d\'ordonnance', [
                'file_path' => $filePath,
                'full_path' => $fullPath,
                'file_exists' => file_exists($fullPath),
                'file_size' => file_exists($fullPath) ? filesize($fullPath) : 'N/A',
                'ordonnance_id' => $ordonnance->id
            ]);

            // Vérifier que le fichier existe
            if (!file_exists($fullPath)) {
                Log::error('Fichier d\'ordonnance non trouvé', [
                    'file_path' => $filePath,
                    'full_path' => $fullPath
                ]);
                return response()->json(['error' => 'Fichier non trouvé'], 404);
            }

            // Déterminer le type MIME du fichier
            $mimeType = mime_content_type($fullPath);
            if (!$mimeType) {
                $mimeType = 'application/octet-stream';
            }

            // Déterminer l'extension du fichier
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            // Déterminer le nom du fichier de téléchargement
            $fileName = 'ordonnance';
            if ($consultationDate) {
                $fileName .= '_' . preg_replace('/[^0-9]/', '', $consultationDate);
            } else {
                $fileName .= '_' . date('Y-m-d');
            }
            $fileName .= '.' . $extension;

            // Retourner le fichier pour téléchargement
            return response()->download($fullPath, $fileName, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du téléchargement de l\'ordonnance', [
                'file_path' => $request->get('file'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Erreur lors du téléchargement',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Permet à un patient de noter un médecin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function noterMedecin(Request $request)
    {
        try {
            // Validation des données d'entrée
            $request->validate([
                'medecin_id' => 'required|integer|exists:users,id',
                'note' => 'required|integer|min:1|max:5',
                'avis' => 'nullable|string|max:1000'
            ]);

            $patientId = Auth::id();
            $medecinId = $request->input('medecin_id');
            $note = $request->input('note');
            $avis = $request->input('avis');
            $previousNote = $request->input('previous_note');

            // Vérifier que l'utilisateur est bien un patient
            /** @var User $patient */
            $patient = Auth::user();
            if (!$patient || !$patient->isPatient()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Seuls les patients peuvent noter les médecins.'
                ], 403);
            }

            // Vérifier que le médecin existe et est bien un médecin
            $medecin = User::find($medecinId);
            if (!$medecin || !$medecin->isMedecin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Médecin non trouvé ou invalide.'
                ], 404);
            }

            // Vérifier que le patient a eu au moins un rendez-vous avec ce médecin (quel que soit le statut)
            $hasRendezVous = RendezVous::where('patient_id', $patientId)
                ->where('medecin_id', $medecinId)
                ->exists();

            if (!$hasRendezVous) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez noter que les médecins avec lesquels vous avez eu des rendez-vous.'
                ], 403);
            }

            // Appeler la méthode métier pour ajouter l'avis
            $result = $this->ajouterAvis($medecinId, $note, $previousNote);

            if ($result['success']) {
                // Log de l'avis pour audit
                Log::info('Avis ajouté avec succès', [
                    'patient_id' => $patientId,
                    'medecin_id' => $medecinId,
                    'note' => $note,
                    'nouveau_score' => $result['nouveau_score'],
                    'nouveau_nbr_avis' => $result['nouveau_nbr_avis']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Médecin noté avec succès !',
                    'action' => $result['action'] ?? null,
                    'note_patient' => (int) ($result['note_patient'] ?? $note),
                    'nouveau_score' => $result['nouveau_score'],
                    'nouveau_nbr_avis' => $result['nouveau_nbr_avis']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 500);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la notation du médecin', [
                'patient_id' => Auth::id(),
                'medecin_id' => $request->input('medecin_id'),
                'note' => $request->input('note'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la notation du médecin.'
            ], 500);
        }
    }

    /**
     * Méthode métier pour ajouter un avis à un médecin
     *
     * Cette méthode implémente la logique métier pour :
     * - Incrémenter le nombre d'avis (nbrAvis) de +1
     * - Recalculer le score moyen du médecin selon la formule :
     *   nouveauScore = ((ancienScore × (nbrAvis - 1)) + nouvelleNote) / nbrAvis
     *
     * @param int $medecinId ID du médecin à noter
     * @param int $note Note attribuée (entre 1 et 5)
     * @return array Résultat de l'opération avec les nouveaux scores
     */
    public function ajouterAvis($medecinId, $note, $previousNote = null)
    {
        try {
            $patientId = Auth::id();

            // Validation des paramètres
            if (!is_numeric($medecinId) || $medecinId <= 0) {
                return [
                    'success' => false,
                    'message' => 'ID du médecin invalide.'
                ];
            }

            if (!is_numeric($note) || $note < 1 || $note > 5) {
                return [
                    'success' => false,
                    'message' => 'La note doit être comprise entre 1 et 5.'
                ];
            }

            $result = DB::transaction(function () use ($medecinId, $note, $patientId, $previousNote) {
                // Récupérer le médecin avec verrouillage pour éviter les conditions de course
                $medecin = User::where('id', $medecinId)
                    ->where('role', 'medecin')
                    ->lockForUpdate()
                    ->first();

                if (!$medecin) {
                    return [
                        'success' => false,
                        'message' => 'Médecin non trouvé.'
                    ];
                }

                // Valeurs actuelles
                $ancienScore = (float) ($medecin->score ?? 0);
                $ancienNbrAvis = (int) ($medecin->nbrAvis ?? 0);

                // Calculs
                if ($previousNote === null) {
                    // Nouveau vote
                    $nouveauNbrAvis = $ancienNbrAvis + 1;
                    $nouveauScore = $ancienNbrAvis === 0
                        ? $note
                        : (($ancienScore * $ancienNbrAvis) + $note) / $nouveauNbrAvis;
                    $action = 'created';
                } else {
                    // Mise à jour de vote existant (ne pas incrémenter nbrAvis)
                    $nouveauNbrAvis = $ancienNbrAvis;
                    if ($ancienNbrAvis === 0) {
                        // Cas de sécurité, mais ne devrait pas arriver si previousNote existe
                        $nouveauScore = $note;
                    } else {
                        // Remplacer l'ancienne note par la nouvelle dans la moyenne
                        $nouveauScore = (($ancienScore * $ancienNbrAvis) - $previousNote + $note) / $ancienNbrAvis;
                    }
                    $action = 'updated';
                }

                // Arrondir le score à 2 décimales
                $nouveauScore = round($nouveauScore, 2);

                // Mettre à jour la table users (score, nbrAvis)
                $medecin->update([
                    'score' => $nouveauScore,
                    'nbrAvis' => $nouveauNbrAvis,
                ]);

                // Log
                Log::info('Notation médecin mise à jour', [
                    'action' => $action,
                    'medecin_id' => $medecinId,
                    'patient_id' => $patientId,
                    'ancien_score' => $ancienScore,
                    'ancien_nbr_avis' => $ancienNbrAvis,
                    'previous_note' => $previousNote,
                    'nouvelle_note' => $note,
                    'nouveau_score' => $nouveauScore,
                    'nouveau_nbr_avis' => $nouveauNbrAvis,
                ]);

                return [
                    'success' => true,
                    'action' => $action,
                    'nouveau_score' => $nouveauScore,
                    'nouveau_nbr_avis' => $nouveauNbrAvis,
                    'note_patient' => (int) $note,
                ];
            });

            return $result;

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout/mise à jour de l\'avis', [
                'medecin_id' => $medecinId,
                'note' => $note,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du score du médecin.'
            ];
        }
    }

    /**
     * Redimensionne une image pour qu'elle s'affiche correctement dans le PDF
     * @param string $imagePath Chemin vers l'image
     * @return string|null Image redimensionnée en base64 ou null si erreur
     */
    private function resizeImageForPDF($imagePath)
    {
        try {
            // Vérifier que l'extension GD est disponible
            if (!extension_loaded('gd')) {
                // Fallback : retourner l'image originale en base64
                return 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($imagePath));
            }

            // Lire l'image
            $imageInfo = getimagesize($imagePath);
            if (!$imageInfo) {
                return null;
            }

            $originalWidth = $imageInfo[0];
            $originalHeight = $imageInfo[1];
            $mimeType = $imageInfo['mime'];

            // Définir les dimensions maximales pour le PDF (A4 portrait)
            $maxWidth = 500;  // Largeur maximale en pixels
            $maxHeight = 600; // Hauteur maximale en pixels

            // Calculer les nouvelles dimensions en conservant le ratio
            if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
                $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
                $newWidth = round($originalWidth * $ratio);
                $newHeight = round($originalHeight * $ratio);
            } else {
                // L'image est déjà de la bonne taille
                $newWidth = $originalWidth;
                $newHeight = $originalHeight;
            }

            // Créer une nouvelle image redimensionnée
            $newImage = imagecreatetruecolor($newWidth, $newHeight);

            // Charger l'image originale selon son type
            switch ($mimeType) {
                case 'image/jpeg':
                    $sourceImage = imagecreatefromjpeg($imagePath);
                    break;
                case 'image/png':
                    $sourceImage = imagecreatefrompng($imagePath);
                    // Préserver la transparence pour les PNG
                    imagealphablending($newImage, false);
                    imagesavealpha($newImage, true);
                    $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                    imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
                    break;
                case 'image/gif':
                    $sourceImage = imagecreatefromgif($imagePath);
                    break;
                default:
                    // Format non supporté, retourner l'image originale
                    return 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($imagePath));
            }

            if (!$sourceImage) {
                return null;
            }

            // Redimensionner l'image
            imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

            // Capturer l'image redimensionnée en base64
            ob_start();
            switch ($mimeType) {
                case 'image/jpeg':
                    imagejpeg($newImage, null, 85); // Qualité 85%
                    break;
                case 'image/png':
                    imagepng($newImage, null, 6); // Compression niveau 6
                    break;
                case 'image/gif':
                    imagegif($newImage);
                    break;
            }
            $imageData = ob_get_contents();
            ob_end_clean();

            // Nettoyer la mémoire
            imagedestroy($sourceImage);
            imagedestroy($newImage);

            // Retourner l'image redimensionnée en base64
            return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);

        } catch (\Exception $e) {
            // En cas d'erreur, retourner l'image originale
            Log::warning('Erreur lors du redimensionnement de l\'image', [
                'image_path' => $imagePath,
                'error' => $e->getMessage()
            ]);
            return 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($imagePath));
        }
    }
}


