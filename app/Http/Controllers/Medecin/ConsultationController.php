<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Ordonnance;

class ConsultationController extends Controller
{
    /**
     * Afficher la liste des consultations avec filtres
     */
    public function index(Request $request)
    {
        // Récupérer le médecin connecté
        /** @var User $user */
        $user = Auth::user();
        $isMedecin = $user->isMedecin();
        if (!$isMedecin) {
            abort(403, 'Accès non autorisé. Vous devez être un médecin.');
        }

        $query = Consultation::with(['rendezVous.patient', 'rendezVous.medecin'])
            ->whereHas('rendezVous', function ($q) use ($user) {
                $q->where('medecin_id', $user->id);
            });

        // Filtres
        if ($request->filled('patient_id')) {
            $query->whereHas('rendezVous', function ($q) use ($request) {
                $q->where('patient_id', $request->patient_id);
            });
        }

        if ($request->filled('type_consultation')) {
            $query->where('type_consultation', $request->type_consultation);
        }

        if ($request->filled('date_debut')) {
            $query->where('date', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date', '<=', $request->date_fin);
        }

        // Recherche par nom du patient
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('rendezVous.patient', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%");
            });
        }

        $consultations = $query->orderBy('date', 'desc')->paginate(10);

        // Données pour les filtres (seulement les patients du médecin)
        $patientIds = RendezVous::where('medecin_id', $user->id)
            ->distinct()
            ->pluck('patient_id');

        $patients = User::whereIn('id', $patientIds)->get();

        $typesConsultation = [
            'premiere' => 'Consultation première fois',
            'routine' => 'Consultation de routine',
            'controle' => 'Consultation de contrôle',
            'urgence' => 'Consultation d\'urgence',
            'suivi' => 'Consultation de suivi'
        ];

        return view('dashMedecin.consultations.index', compact(
            'consultations',
            'patients',
            'typesConsultation'
        ));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        // Récupérer le médecin connecté
        /** @var User $user */
        $user = Auth::user();
        $isMedecin = $user->isMedecin();
        if (!$isMedecin) {
            abort(403, 'Accès non autorisé. Vous devez être un médecin.');
        }
        $medecinId = $user->id;
        $medecinConnecte = $user;

        // Récupérer le patient spécifié
        $patientId = $request->get('patient_id');
        $selectedPatient = null;
        if ($patientId) {
            $selectedPatient = User::find($patientId);
        }

        // Récupérer les rendez-vous du patient avec le médecin connecté
        $rendezVous = collect();
        if ($selectedPatient) {
            $rendezVous = RendezVous::with(['patient', 'medecin'])
                ->where('patient_id', $selectedPatient->id)
                ->where('medecin_id', $medecinId)
                ->where('statut', 'confirmed')
                ->orderBy('date_debut', 'desc')
                ->get();
        }

        $typesConsultation = [
            'premiere' => 'Consultation première fois',
            'routine' => 'Consultation de routine',
            'controle' => 'Consultation de contrôle',
            'urgence' => 'Consultation d\'urgence',
            'suivi' => 'Consultation de suivi'
        ];

        // Si un rendez-vous est spécifié
        $rendezVousId = $request->get('rendez_vous_id');
        $selectedRendezVous = null;
        if ($rendezVousId) {
            $selectedRendezVous = RendezVous::with(['patient', 'medecin'])->find($rendezVousId);
        }

        return view('dashMedecin.consultations.create', compact(
            'selectedPatient',
            'medecinConnecte',
            'rendezVous',
            'typesConsultation',
            'selectedRendezVous'
        ));
    }

    /**
     * Enregistrer une nouvelle consultation
     */
    public function store(Request $request)
    {
        // Accepter les noms des champs tels que présents dans les vues Blade
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:users,id',
            'rendezvous_id' => 'nullable|exists:rendez_vous,id',
            'rendez_vous_id' => 'sometimes|exists:rendez_vous,id',
            'date_consultation' => 'required|date',
            'type_consultation' => 'required|string',
            'motif_consultation' => 'nullable|string',
            'symptomes' => 'nullable|string',
            'tension_arterielle' => 'nullable|string',
            'frequence_cardiaque' => 'nullable|integer|min:0',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'saturation_o2' => 'nullable|integer|min:0|max:100',
            'score_glasgow' => 'nullable|integer|min:3|max:15',
            'examen_physique' => 'nullable|string',
            'diagnostic_presume' => 'nullable|string',
            'medicaments_prescrits' => 'nullable|string',
            'propositions_suivi' => 'nullable|string',
            'instructions_particulieres' => 'nullable|string',
            'poids' => 'nullable|numeric|min:0',
            'taille' => 'nullable|numeric|min:0',
            'habitudes_vie' => 'nullable|string',
            'traitement_actuel' => 'nullable|string',
            'evolution_symptomes' => 'nullable|string',
            'effets_secondaires' => 'nullable|string',
            'examens_controle' => 'nullable|string',
            'symptomes_aigus' => 'nullable|string',
            'debut_symptomes' => 'nullable|date',
            'gravite' => 'nullable|string',
            'orientation_patient' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $v = $validator->validated();

        // Mapper les champs des vues vers les colonnes du modèle
        $data = [];
        $data['rendezvous_id'] = $v['rendezvous_id'] ?? $v['rendez_vous_id'] ?? null;
        // Normaliser la date (colonne DATE)
        $data['date'] = \Carbon\Carbon::parse($v['date_consultation'])->toDateString();

        // Mapper le type UI -> enum DB
        $uiType = strtolower($v['type_consultation']);
        $typeMap = [
            'premiere' => 'first_consultation',
            'routine' => 'routine',
            'controle' => 'controle',
            'urgence' => 'urgent',
            'suivi' => 'routine',
        ];
        $data['type'] = $typeMap[$uiType] ?? 'other';

        $data['motif'] = $v['motif_consultation'] ?? null;
        $data['symptomes'] = $v['symptomes'] ?? null;
        $data['tension_arterielle'] = $v['tension_arterielle'] ?? null;
        $data['frequence_cardiaque'] = $v['frequence_cardiaque'] ?? null;
        $data['temperature'] = $v['temperature'] ?? null;
        $data['saturation_o2'] = $v['saturation_o2'] ?? null;
        $data['score_glasgow'] = $v['score_glasgow'] ?? null;
        $data['examen_physique'] = $v['examen_physique'] ?? null;
        $data['diagnostic_presume'] = $v['diagnostic_presume'] ?? null;
        $data['medicaments_prescrits'] = $v['medicaments_prescrits'] ?? null;
        $data['propositions_suivi'] = $v['propositions_suivi'] ?? null;
        $data['instructions_particulieres'] = $v['instructions_particulieres'] ?? null;
        $data['poids'] = $v['poids'] ?? null;
        $data['taille'] = $v['taille'] ?? null;
        $data['habitudes_vie'] = $v['habitudes_vie'] ?? null;
        $data['traitement_actuel'] = $v['traitement_actuel'] ?? null;
        $data['evolution_symptomes'] = $v['evolution_symptomes'] ?? null;
        $data['effets_secondaires'] = $v['effets_secondaires'] ?? null;
        $data['examens_controle'] = $v['examens_controle'] ?? null;
        $data['symptomes_aigus'] = $v['symptomes_aigus'] ?? null;
        $data['debut_symptomes'] = $v['debut_symptomes'] ?? null;
        $data['gravite'] = $v['gravite'] ?? null;
        $data['orientation_patient'] = $v['orientation_patient'] ?? null;

        // Assigner automatiquement le médecin connecté
        $medecinConnecte = Auth::user();
        if (!$medecinConnecte || $medecinConnecte->role !== 'medecin') {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
            }
            abort(403, 'Accès non autorisé. Vous devez être un médecin.');
        }
        // Associer le dossier médical du patient
        $patientId = (int) $v['patient_id'];
        $dossier = \App\Models\DossierMedical::firstOrCreate(['patient_id' => $patientId]);
        $data['dossier_medical_id'] = $dossier->id;

        // Optionnel: vérifier que le rendez-vous appartient au même médecin
        if (!empty($data['rendezvous_id'])) {
            $rv = RendezVous::find($data['rendezvous_id']);
            if (!$rv || $rv->medecin_id !== $medecinConnecte->id || $rv->patient_id !== $patientId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rendez-vous invalide pour ce patient ou ce médecin'
                ], 422);
            }
            // Optionnel: exiger un RDV confirmé
            if ($rv->statut !== 'confirmed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Le rendez-vous doit être confirmé pour créer une consultation'
                ], 422);
            }
        }

        // Calculer l'IMC si poids et taille sont fournis
        if (!empty($data['poids']) && !empty($data['taille'])) {
            $data['imc'] = $data['poids'] / pow($data['taille'] / 100, 2);
        }

        try {
            $consultation = Consultation::create($data);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Consultation créée avec succès',
                    'consultation' => $consultation->load(['dossierMedical.patient', 'rendezVous.patient', 'rendezVous.medecin'])
                ]);
            }

            return redirect()
                ->route('medecin.consultations.show', $consultation)
                ->with('success', 'Consultation créée avec succès');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la consultation',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'Erreur lors de la création de la consultation'])->withInput();
        }
    }

    /**
     * Afficher une consultation spécifique
     */
    public function show(Consultation $consultation)
    {
        $consultation->load(['rendezVous.patient', 'rendezVous.medecin', 'dossierMedical.patient', 'ordonnances']);

        // Support JSON for AJAX consumers
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'consultation' => $consultation,
            ]);
        }

        $typesConsultation = [
            'premiere' => 'Consultation première fois',
            'routine' => 'Consultation de routine',
            'controle' => 'Consultation de contrôle',
            'urgence' => 'Consultation d\'urgence',
            'suivi' => 'Consultation de suivi'
        ];

        return view('dashMedecin.consultations.show', compact('consultation', 'typesConsultation'));
    }

    /**
     * Créer/MàJ l'ordonnance d'une consultation
     */
    public function upsertOrdonnance(Request $request, Consultation $consultation)
    {
        // Autorisation minimum: médecin propriétaire de la consultation
        /** @var User $user */
        $user = Auth::user();
        if (!$user || !$user->isMedecin()) {
            return back()->with('error', 'Accès non autorisé');
        }

        $rules = [
            'medicaments' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
        $data = Validator::make($request->all(), $rules)->validate();

        // Convertir medicaments en array si string JSON
        $payload = [];
        if (!empty($data['medicaments'])) {
            $decoded = json_decode($data['medicaments'], true);
            $payload['medicaments'] = is_array($decoded) ? $decoded : [$data['medicaments']];
        }
        if (!empty($data['notes'])) {
            $payload['notes'] = $data['notes'];
        }

        // Upload fichier si fourni
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('ordonnances', 'public');
            $payload['file'] = $path;
        }

        // Upsert: une seule ordonnance par consultation
        $ordonnance = Ordonnance::updateOrCreate(
            ['consultation_id' => $consultation->id],
            array_merge(['consultation_id' => $consultation->id], $payload)
        );

        return redirect()->route('medecin.consultations.show', $consultation)
            ->with('success', 'Ordonnance enregistrée avec succès');
    }

    /**
     * Supprimer l'ordonnance de la consultation
     */
    public function deleteOrdonnance(Consultation $consultation)
    {
        $ordonnance = Ordonnance::where('consultation_id', $consultation->id)->first();
        if ($ordonnance) {
            if ($ordonnance->file) {
                Storage::disk('public')->delete($ordonnance->file);
            }
            $ordonnance->delete();
        }
        return redirect()->route('medecin.consultations.show', $consultation)
            ->with('success', 'Ordonnance supprimée avec succès');
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Consultation $consultation)
    {
        $consultation->load(['rendezVous.patient', 'rendezVous.medecin', 'dossierMedical.patient']);

        // Ces listes ne nécessitent pas de relation 'user' (User est déjà l'entité)
        $patients = User::where('role', 'patient')->get();
        $medecins = User::where('role', 'medecin')->get();
        $rendezVous = RendezVous::with(['patient', 'medecin'])->get();
        $typesConsultation = [
            'premiere' => 'Consultation première fois',
            'routine' => 'Consultation de routine',
            'controle' => 'Consultation de contrôle',
            'urgence' => 'Consultation d\'urgence',
            'suivi' => 'Consultation de suivi'
        ];

        return view('dashMedecin.consultations.edit', compact(
            'consultation',
            'patients',
            'medecins',
            'rendezVous',
            'typesConsultation'
        ));
    }

    /**
     * Mettre à jour une consultation
     */
    public function update(Request $request, Consultation $consultation)
    {
        $validator = Validator::make($request->all(), [
            // Identifiants non modifiables via l'édition
            'rendezvous_id' => 'sometimes|nullable|exists:rendez_vous,id',
            'date_consultation' => 'required|date',
            'type_consultation' => 'required|string',
            'motif_consultation' => 'nullable|string',
            'symptomes' => 'nullable|string',
            'tension_arterielle' => 'nullable|string',
            'frequence_cardiaque' => 'nullable|integer|min:0',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'saturation_o2' => 'nullable|integer|min:0|max:100',
            'score_glasgow' => 'nullable|integer|min:3|max:15',
            'examen_physique' => 'nullable|string',
            'diagnostic_presume' => 'nullable|string',
            'medicaments_prescrits' => 'nullable|string',
            'propositions_suivi' => 'nullable|string',
            'instructions_particulieres' => 'nullable|string',
            'poids' => 'nullable|numeric|min:0',
            'taille' => 'nullable|numeric|min:0',
            'habitudes_vie' => 'nullable|string',
            'traitement_actuel' => 'nullable|string',
            'evolution_symptomes' => 'nullable|string',
            'effets_secondaires' => 'nullable|string',
            'examens_controle' => 'nullable|string',
            'symptomes_aigus' => 'nullable|string',
            'debut_symptomes' => 'nullable|date',
            'gravite' => 'nullable|string',
            'orientation_patient' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $v = $validator->validated();

        // Mapper vers le modèle
        $data = [];
        $data['date'] = $v['date_consultation'];
        $uiType = strtolower($v['type_consultation']);
        $typeMap = [
            'premiere' => 'first_consultation',
            'routine' => 'routine',
            'controle' => 'controle',
            'urgence' => 'urgent',
            'suivi' => 'routine',
        ];
        $data['type'] = $typeMap[$uiType] ?? 'other';
        $data['motif'] = $v['motif_consultation'] ?? null;
        $data['symptomes'] = $v['symptomes'] ?? null;
        $data['tension_arterielle'] = $v['tension_arterielle'] ?? null;
        $data['frequence_cardiaque'] = $v['frequence_cardiaque'] ?? null;
        $data['temperature'] = $v['temperature'] ?? null;
        $data['saturation_o2'] = $v['saturation_o2'] ?? null;
        $data['score_glasgow'] = $v['score_glasgow'] ?? null;
        $data['examen_physique'] = $v['examen_physique'] ?? null;
        $data['diagnostic_presume'] = $v['diagnostic_presume'] ?? null;
        $data['medicaments_prescrits'] = $v['medicaments_prescrits'] ?? null;
        $data['propositions_suivi'] = $v['propositions_suivi'] ?? null;
        $data['instructions_particulieres'] = $v['instructions_particulieres'] ?? null;
        $data['poids'] = $v['poids'] ?? null;
        $data['taille'] = $v['taille'] ?? null;
        $data['habitudes_vie'] = $v['habitudes_vie'] ?? null;
        $data['traitement_actuel'] = $v['traitement_actuel'] ?? null;
        $data['evolution_symptomes'] = $v['evolution_symptomes'] ?? null;
        $data['effets_secondaires'] = $v['effets_secondaires'] ?? null;
        $data['examens_controle'] = $v['examens_controle'] ?? null;
        $data['symptomes_aigus'] = $v['symptomes_aigus'] ?? null;
        $data['debut_symptomes'] = $v['debut_symptomes'] ?? null;
        $data['gravite'] = $v['gravite'] ?? null;
        $data['orientation_patient'] = $v['orientation_patient'] ?? null;

        // Préserve les identifiants de relation
        $data['dossier_medical_id'] = $consultation->dossier_medical_id;
        $data['rendezvous_id'] = $consultation->rendezvous_id; // non modifié via écran edit

        // Calculer l'IMC si poids et taille sont fournis
        if (!empty($data['poids']) && !empty($data['taille'])) {
            $data['imc'] = $data['poids'] / pow($data['taille'] / 100, 2);
        } else {
            $data['imc'] = null;
        }

        try {
            $consultation->update($data);

            // Déterminer le patient pour la redirection vers le dossier
            $consultation->load(['dossierMedical.patient', 'rendezVous.patient']);
            $patientId = optional($consultation->dossierMedical->patient)->id
                ?? optional($consultation->rendezVous->patient)->id;
            $redirectUrl = $patientId
                ? route('medecin.dossiermedical', ['patient_id' => $patientId])
                : route('medecin.consultations.show', $consultation);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Consultation mise à jour avec succès',
                    'redirect_url' => $redirectUrl,
                ]);
            }

            return redirect($redirectUrl)
                ->with('success', 'Consultation mise à jour avec succès');
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de la consultation',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour de la consultation'])->withInput();
        }
    }

    /**
     * Supprimer une consultation
     */
    public function destroy(Consultation $consultation)
    {
        try {
            $consultation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Consultation supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la consultation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les consultations d'un patient spécifique
     */
    public function getPatientConsultations(User $patient)
    {
        $consultations = $patient->consultations()
            ->with(['medecin.user'])
            ->orderBy('date_consultation', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'consultations' => $consultations
        ]);
    }

    /**
     * Obtenir les consultations d'un médecin spécifique
     */
    public function getMedecinConsultations(User $medecin)
    {
        $consultations = $medecin->consultations()
            ->with(['patient.user'])
            ->orderBy('date_consultation', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'consultations' => $consultations
        ]);
    }

    /**
     * Obtenir les consultations d'un patient spécifique avec filtres (pour le dossier médical)
     */
    public function getPatientConsultationsList(Request $request, User $patient)
    {
        // Vérifier le médecin connecté (via role)
        $user = Auth::user();
        if (!$user || ($user->role ?? null) !== 'medecin') {
            return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
        }

        // Construire la requête via la relation rendezVous (la table consultations n'a pas patient_id/medecin_id)
        $query = Consultation::with(['rendezVous.patient', 'rendezVous.medecin'])
            ->whereHas('rendezVous', function ($q) use ($patient, $user) {
                $q->where('patient_id', $patient->id)
                  ->where('medecin_id', $user->id);
            })
            ->orderBy('date', 'desc');

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('motif', 'like', "%{$search}%")
                  ->orWhere('diagnostic_presume', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            // le front envoie routine/urgence/suivi/controle, mapper vers enum DB
            $map = [
                'premiere' => 'first_consultation',
                'routine' => 'routine',
                'controle' => 'controle',
                'urgence' => 'urgent',
                'suivi' => 'routine',
            ];
            $dbType = $map[$request->type] ?? $request->type;
            $query->where('type', $dbType);
        }

        if ($request->filled('period')) {
            $now = now();
            switch ($request->period) {
                case 'semaine':
                    $query->where('date', '>=', $now->copy()->startOfWeek());
                    break;
                case 'mois':
                    $query->where('date', '>=', $now->copy()->startOfMonth());
                    break;
                case 'annee':
                    $query->where('date', '>=', $now->copy()->startOfYear());
                    break;
            }
        }

        $items = $query->get();

        // Adapter les champs attendus par le front (dossierMedical.blade.js)
        $reverseTypeMap = [
            'first_consultation' => 'premiere',
            'routine' => 'routine',
            'controle' => 'controle',
            'urgent' => 'urgence',
            'other' => 'routine',
        ];

        $consultations = $items->map(function ($c) use ($reverseTypeMap) {
            return [
                'id' => $c->id,
                'date_consultation' => optional($c->date)->format('Y-m-d'),
                'type_consultation' => $reverseTypeMap[$c->type] ?? 'routine',
                'motif_consultation' => $c->motif,
                'symptomes' => $c->symptomes,
                'tension_arterielle' => $c->tension_arterielle,
                'frequence_cardiaque' => $c->frequence_cardiaque,
                'temperature' => $c->temperature,
                'saturation_o2' => $c->saturation_o2,
                'score_glasgow' => $c->score_glasgow,
                'examen_physique' => $c->examen_physique,
                'diagnostic_presume' => $c->diagnostic_presume,
                'medicaments_prescrits' => $c->medicaments_prescrits,
                'propositions_suivi' => $c->propositions_suivi,
                'instructions_particulieres' => $c->instructions_particulieres,
                'symptomes_aigus' => $c->symptomes_aigus,
                'debut_symptomes' => $c->debut_symptomes,
                'gravite' => $c->gravite,
                'orientation_patient' => $c->orientation_patient,
            ];
        });

        return response()->json([
            'success' => true,
            'consultations' => $consultations,
        ]);
    }

    /**
     * Obtenir les rendez-vous du jour pour un patient
     */
    public function getPatientRendezVousToday(Request $request, User $patient)
    {
        // Récupérer le médecin connecté
        $medecinConnecte = Auth::user() ? Auth::user()->medecin : null;
        if (!$medecinConnecte) {
            return response()->json(['success' => false, 'message' => 'Accès non autorisé'], 403);
        }

        $today = now()->format('Y-m-d');

        $rendezVous = RendezVous::with(['patient.user', 'medecin.user'])
            ->where('patient_id', $patient->id)
            ->where('medecin_id', $medecinConnecte->id)
            ->where('statut', 'confirmé')
            ->whereDate('date_debut', $today)
            ->orderBy('date_debut', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'rendezVous' => $rendezVous
        ]);
    }

    /**
     * Rediriger vers la consultation associée à un rendez-vous, ou vers la création si absente
     */
    public function redirectToConsultationFromRendezVous(RendezVous $rendezVous)
    {
        /** @var User $user */
        $user = Auth::user();
        $medecinConnecte = $user ? $user->isMedecin() : null;
        if (!$medecinConnecte || $rendezVous->medecin_id !== $user->id) {
            abort(403, 'Accès non autorisé.');
        }

        $consultation = Consultation::where('rendezvous_id', $rendezVous->id)->first();
        if ($consultation) {
            return redirect()->route('medecin.consultations.show', $consultation);
        }

        return redirect()->route('medecin.consultations.create', [
            'patient_id' => $rendezVous->patient_id,
            'rendez_vous_id' => $rendezVous->id,
        ]);
    }
}
