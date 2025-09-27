<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\RendezVous;
use Carbon\Carbon;

class TimelineController extends Controller
{
    /**
     * Récupère les données du timeline pour la navbar
     */
    public function getTimelineData()
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            $medecin = $user->isMedecin();
            if (!$user) {
                return response()->json(['error' => 'Utilisateur non connecté'], 401);
            }

            if (!$medecin) {
                return response()->json(['error' => 'Accès non autorisé - Utilisateur non médecin'], 403);
            }

            $today = Carbon::today();
            $now = Carbon::now();

        // Récupérer tous les rendez-vous du jour du médecin
        $rendezVous = RendezVous::with(['patient'])
            ->where('medecin_id', $user->id)
            ->whereDate('date_debut', $today)
            ->where('statut', '!=', 'payed')
            ->orderBy('date_debut')
            ->get();

        // Créer un timeline de 8h à 17h
        $timeline = [];
        $currentHour = 8;
        $endHour = 17;

        // Remplir le timeline avec les créneaux horaires
        for ($hour = $currentHour; $hour <= $endHour; $hour++) {
            $timeSlot = sprintf('%02d:00', $hour);
            $nextTimeSlot = sprintf('%02d:00', $hour + 1);

            // Trouver les rendez-vous pour ce créneau
            $rdvInSlot = $rendezVous->filter(function ($rdv) use ($hour) {
                $rdvHour = Carbon::parse($rdv->date_debut)->hour;
                return $rdvHour === $hour;
            });

            $timeline[] = [
                'time' => $timeSlot,
                'next_time' => $nextTimeSlot,
                'hour' => $hour,
                'is_current' => $now->hour === $hour,
                'is_past' => $now->hour > $hour,
                'is_future' => $now->hour < $hour,
                'rendez_vous' => $rdvInSlot->map(function ($rdv) use ($now) {
                    $startTime = Carbon::parse($rdv->date_debut);
                    $endTime = Carbon::parse($rdv->date_fin ?? $startTime->copy()->addMinutes(30));
                    $duration = $startTime->diffInMinutes($endTime);

                    return [
                        'id' => $rdv->id,
                        'patient_name' => $rdv->patient->prenom . ' ' . $rdv->patient->nom,
                        'patient_photo' => $rdv->patient && $rdv->patient->profile_photo_path
                            ? asset('storage/' . $rdv->patient->profile_photo_path)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($rdv->patient->prenom . ' ' . $rdv->patient->nom),
                        'start_time' => $startTime->format('H:i'),
                        'end_time' => $endTime->format('H:i'),
                        'duration' => $duration,
                        'type' => $rdv->type ?? 'consultation',
                        'statut' => $rdv->statut,
                        'is_active' => $now->between($startTime, $endTime),
                        'is_late' => $now->gt($endTime) && !in_array($rdv->statut, ['completed'], true),
                        'has_consultation_link' => !empty($rdv->lien_en_ligne),
                        'lien_en_ligne' => $rdv->lien_en_ligne
                    ];
                })->values()
            ];
        }

        // Trouver le prochain rendez-vous
        $nextRendezVous = $rendezVous->filter(function ($rdv) use ($now) {
            return Carbon::parse($rdv->date_debut)->gt($now);
        })->first();

        // Trouver le rendez-vous actuel
        $currentRendezVous = $rendezVous->filter(function ($rdv) use ($now) {
            $startTime = Carbon::parse($rdv->date_debut);
            $endTime = Carbon::parse($rdv->date_fin ?? $startTime->copy()->addMinutes(30));
            return $now->between($startTime, $endTime);
        })->first();

        return response()->json([
            'timeline' => $timeline,
            'current_time' => $now->format('H:i'),
            'current_date' => $today->format('d M'),
            'current_hour' => $now->hour,
            'next_rendez_vous' => $nextRendezVous ? [
                'id' => $nextRendezVous->id,
                'time' => Carbon::parse($nextRendezVous->date_debut)->format('H:i'),
                'patient_name' => $nextRendezVous->patient->prenom . ' ' . $nextRendezVous->patient->nom,
                'patient_photo' => $nextRendezVous->patient && $nextRendezVous->patient->profile_photo_path
                    ? asset('storage/' . $nextRendezVous->patient->profile_photo_path)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($nextRendezVous->patient->prenom . ' ' . $nextRendezVous->patient->nom),
                'minutes_until' => $now->diffInMinutes(Carbon::parse($nextRendezVous->date_debut), false)
            ] : null,
            'current_rendez_vous' => $currentRendezVous ? [
                'id' => $currentRendezVous->id,
                'time' => Carbon::parse($currentRendezVous->date_debut)->format('H:i'),
                'patient_name' => $currentRendezVous->patient->prenom . ' ' . $currentRendezVous->patient->nom,
                'patient_photo' => $currentRendezVous->patient && $currentRendezVous->patient->profile_photo_path
                    ? asset('storage/' . $currentRendezVous->patient->profile_photo_path)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($currentRendezVous->patient->prenom . ' ' . $currentRendezVous->patient->nom),
                'end_time' => Carbon::parse($currentRendezVous->date_fin ?? Carbon::parse($currentRendezVous->date_debut)->addMinutes(30))->format('H:i'),
                'has_consultation_link' => !empty($currentRendezVous->lien_en_ligne),
                'lien_en_ligne' => $currentRendezVous->lien_en_ligne
            ] : null,
            'total_rendez_vous' => $rendezVous->count(),
            'completed_rendez_vous' => $rendezVous->where('statut', 'completed')->count(),
            'pending_rendez_vous' => $rendezVous->where('statut', 'pending')->count()
        ]);
        } catch (\Exception $e) {
            Log::error('TimelineController Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Erreur interne du serveur: ' . $e->getMessage()], 500);
        }
    }
}
