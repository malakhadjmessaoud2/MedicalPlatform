<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Patient\RendezVousController as PatientRendezVousController;
use App\Http\Controllers\Medecin\RendezVousController as MedecinRendezVousController;
use App\Http\Controllers\Medecin\ConsultationController;
use App\Http\Controllers\Medecin\TimelineController;
use App\Http\Controllers\DashboardController;

use Illuminate\Support\Facades\Auth;
use App\Models\RendezVous;
use Carbon\Carbon;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes pour les médecins
Route::prefix('medecins')->group(function () {
    Route::get('/by-specialite', [PatientRendezVousController::class, 'getMedecinsBySpecialite'])
        ->name('api.medecins.by-specialite');

    Route::get('/{medecin}/creneaux-disponibles', [PatientRendezVousController::class, 'getCreneauxDisponibles'])
        ->name('api.medecin.creneaux-disponibles');

    Route::get('/{medecin}', [PatientRendezVousController::class, 'getMedecinDetails'])
        ->name('api.medecin.details');
});

Route::middleware(['web', 'auth', 'role:medecin'])->prefix('medecin')->group(function () {
    //Route::middleware(['auth:sanctum', 'role:medecin'])->prefix('medecin')->group(function () {
    // Timeline pour la navbar
    Route::get('/timeline', [TimelineController::class, 'getTimelineData'])->name('api.medecin.timeline.data');

    // Rendez-vous - CRUD JSON
    Route::get('/rendez-vous', [MedecinRendezVousController::class, 'getEvenements'])->name('api.medecin.rendez-vous.evenements');
    Route::post('/rendez-vous', [MedecinRendezVousController::class, 'store'])->name('api.medecin.rendez-vous.store');
    Route::get('/rendez-vous/{rendezVous}', [MedecinRendezVousController::class, 'show'])->name('api.medecin.rendez-vous.show');
    Route::put('/rendez-vous/{rendezVous}', [MedecinRendezVousController::class, 'update'])->name('api.medecin.rendez-vous.update');
    Route::delete('/rendez-vous/{rendezVous}', [MedecinRendezVousController::class, 'destroy'])->name('api.medecin.rendez-vous.destroy');
    Route::put('/rendez-vous/{rendezVous}/statut', [MedecinRendezVousController::class, 'updateStatus'])->name('api.medecin.rendez-vous.updateStatus');
    Route::post('/rendez-vous/{rendezVous}/creer-lien-consultation', [MedecinRendezVousController::class, 'creerLienConsultation'])->name('api.medecin.rendez-vous.creer-lien-consultation');

    // Patients - listes et recherches
    Route::get('/patients', [MedecinRendezVousController::class, 'getPatients'])->name('api.medecin.patients.list');

    // Consultations - données JSON pour dossiers, etc.
    Route::get('/patients/{patient}/consultations', [ConsultationController::class, 'getPatientConsultations'])->name('api.consultations.patient');
    Route::get('/medecins/{medecin}/consultations', [ConsultationController::class, 'getMedecinConsultations'])->name('api.consultations.medecin');
    Route::get('/patients/{patient}/consultations/list', [ConsultationController::class, 'getPatientConsultationsList'])->name('api.consultations.patient.list');
    Route::get('/patients/{patient}/rendez-vous/today', [ConsultationController::class, 'getPatientRendezVousToday'])->name('api.consultations.patient.rendez-vous.today');
});

// API protégée pour le dashboard patient (JSON uniquement)
Route::middleware(['web', 'auth', 'role:patient'])->prefix('patient')->group(function () {
    // Médecins par spécialité
    Route::get('/medecins/by-specialite', [PatientRendezVousController::class, 'getMedecinsBySpecialite'])
        ->name('api.patient.medecins.by-specialite');

    // Détails d'un médecin
    Route::get('/medecins/{medecin}', [PatientRendezVousController::class, 'getMedecinDetails'])
        ->name('api.patient.medecin.details');

    // Créneaux disponibles d'un médecin
    Route::get('/medecins/{medecin}/creneaux-disponibles', [PatientRendezVousController::class, 'getCreneauxDisponibles'])
        ->name('api.patient.medecin.creneaux-disponibles');
});
