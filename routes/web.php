<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Patient\RendezVousController as PatientRendezVousController;
use App\Http\Controllers\Medecin\RendezVousController as MedecinRendezVousController;

use App\Http\Controllers\Medecin\ConsultationController;
use App\Http\Controllers\Medecin\TimelineController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Medecin\PatientController;
use App\Http\Controllers\Pharmacie\DashboardController as PharmacieDashboardController;
use App\Http\Controllers\Donateur\DashboardController as DonateurDashboardController;
use App\Http\Controllers\Medecin\DashboardController as MedecinDashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Patient\DossierController;
use App\Http\Controllers\Patient\PaiementController;
use App\Http\Controllers\Admin\GestionMedecinController;

use Illuminate\Support\Facades\Auth;
use App\Models\RendezVous;

Route::get('/', [DashboardController::class, 'welcome'])->name('welcome');

// Page d'attente d'activation pour les médecins
Route::get('/activation-pending', function () {
    return view('auth.activation-pending');
})->name('activation.pending');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', function () {
        return view('auth.register-choice');
    })->name('register');

    Route::get('/register/medecin', [App\Http\Controllers\Auth\RegisteredUserController::class, 'createMedecin'])
        ->name('register.medecin');

    Route::get('/register/patient', [App\Http\Controllers\Auth\RegisteredUserController::class, 'createPatient'])
        ->name('register.patient');

    Route::get('/register/pharmacie', [App\Http\Controllers\Auth\RegisteredUserController::class, 'createPharmacie'])
        ->middleware('guest')
        ->name('register.pharmacie');

    Route::get('/register/donateur', [App\Http\Controllers\Auth\RegisteredUserController::class, 'createDonateur'])
        ->middleware('guest')
        ->name('register.donateur');

    Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])
        ->name('register.store');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'redirect.role'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::middleware(['auth', 'role:operateurpharmacie'])->prefix('pharmacie')->name('pharmacie.')->group(function () {
    Route::get('/dashboard', [PharmacieDashboardController::class, 'index'])->name('dashboard');
    Route::get('/stock', [PharmacieDashboardController::class, 'stocks'])->name('stock');
    Route::get('/commandes', [PharmacieDashboardController::class, 'commandes'])->name('commandes');
    Route::get('/commandes/validation',  [PharmacieDashboardController::class, 'validationCommande'])->name('validationCommande');
    Route::get('/dons', [PharmacieDashboardController::class, 'donsReçus'])->name('donsReçus');
    Route::get('/donateurs', [PharmacieDashboardController::class, 'donateurs'])->name('donateurs');
    Route::get('/demandes-dons', [PharmacieDashboardController::class, 'demandesDons'])->name('demandesDons');
});

Route::middleware(['auth', 'role:donateur'])->prefix('donateur')->name('donateur.')->group(function () {
    Route::get('/dashboard', [DonateurDashboardController::class, 'index'])->name('dashboard');
    Route::get('/mes-dons', [DonateurDashboardController::class, 'dons'])->name('dons');
    Route::get('/nouveau-don', [DonateurDashboardController::class, 'nouveauDon'])->name('nouveau-don');
    Route::get('/suivi', [DonateurDashboardController::class, 'suivi'])->name('suivi');
    Route::get('/rapports', [DonateurDashboardController::class, 'rapports'])->name('rapports');
    Route::get('/notifications', [DonateurDashboardController::class, 'notifications'])->name('notifications');
    Route::get('/parametres', [DonateurDashboardController::class, 'parametres'])->name('parametres');
    Route::get('/aide', [DonateurDashboardController::class, 'aides'])->name('aide');
});

Route::middleware(['auth', 'role:medecin'])->prefix('medecin')->name('medecin.')->group(function () {
    Route::get('/dashboard', [MedecinDashboardController::class, 'index'])->name('dashboard');

    Route::resource('rendez-vous', MedecinRendezVousController::class);
    Route::get('/patients', [PatientController::class, 'index'])->name('patients');
    Route::get('/dossiers-medicaux', [PatientController::class, 'dossiersMedicaux'])->name('dossiers.medicaux');
    Route::get('/dossiers/{dossier}', [PatientController::class, 'showDossierMedicalJson'])->name('dossier.show');
    Route::get('/dossier', [PatientController::class, 'showDossierMedical'])->name('dossiermedical');
    Route::post('/dossier-medical/update', [PatientController::class, 'updateDossierMedical'])->name('dossier.update');
    // Agenda & Rendez-vous
    Route::controller(MedecinRendezVousController::class)->group(function () {
        Route::get('/agenda', 'index')->name('agenda');
        // Les routes JSON ont été déplacées vers routes/api.php
    });
    Route::get('/traitements', [MedecinDashboardController::class, 'SuiviTraitements'])->name('traitements');
    Route::get('/prestations', [MedecinDashboardController::class, 'Prestations'])->name('prestations');
    Route::get('/communication', [MedecinDashboardController::class, 'communication'])->name('communication');
    Route::resource('consultations', ConsultationController::class);
    // Ordonnance embedded in consultation
    Route::post('/consultations/{consultation}/ordonnance', [ConsultationController::class, 'upsertOrdonnance'])->name('consultations.ordonnance.upsert');
    Route::delete('/consultations/{consultation}/ordonnance', [ConsultationController::class, 'deleteOrdonnance'])->name('consultations.ordonnance.delete');
    // Redirection vers consultation depuis un rendez-vous
    Route::get('/rendez-vous/{rendezVous}/consultation', [ConsultationController::class, 'redirectToConsultationFromRendezVous'])->name('rendezvous.to.consultation');
    // Route pour récupérer les rendez-vous du jour
    Route::get('/rendez-vous-du-jour', [MedecinDashboardController::class, 'getRendezVousDuJour'])->name('rendez-vous-du-jour');
});
// Endpoints JSON utilisés par le dashboard médecin (protégés par session web)
Route::middleware(['auth', 'role:medecin'])->prefix('api/medecin')->group(function () {
    Route::get('/rendez-vous-du-jour', [MedecinDashboardController::class, 'getRendezVousDuJour']);
    Route::get('/timeline', [TimelineController::class, 'getTimelineData']);
});



Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');

    Route::get('/rendez-vous/create', [PatientDashboardController::class, 'createRendezVous'])->name('rendez-vous.create');

    Route::get('/rendez-vous', [PatientRendezVousController::class, 'indexPatient'])
        ->name('rendez-vous.index');
    Route::get('/rendez-vous/{rendezVous}/payer', [PatientRendezVousController::class, 'confirmationPayment'])
        ->name('rendez-vous.payer');

    Route::get('/rendez-vous/{rendezVous}/payment', [PaiementController::class, 'create'])
        ->name('rendez-vous.payment');

    Route::post('/rendez-vous', [PatientRendezVousController::class, 'patientRendezVousStore'])
        ->name('rendez-vous.store');

    Route::put('/rendez-vous/{rendezVous}/cancel', [PatientRendezVousController::class, 'cancelRendezVous'])
        ->name('rendez-vous.cancel');

    // Dossier patient via contrôleur
    Route::get('/dossier', [DossierController::class, 'index'])->name('dossier');
    Route::get('/ordonnance/download', [DossierController::class, 'downloadOrdonnance'])->name('ordonnance.download');
    Route::get('/ordonnance/view', [DossierController::class, 'viewOrdonnance'])->name('ordonnance.view');

    // Notation des médecins
    Route::post('/medecin/noter', [DossierController::class, 'noterMedecin'])->name('medecin.noter');
    Route::get('/messages', [PatientDashboardController::class, 'messages'])->name('messages');
    Route::get('/medicaments', [PatientDashboardController::class, 'medicaments'])->name('medicaments');

    // Commandes
    Route::get('/commandes', [PatientDashboardController::class, 'commandes'])->name('commandes');

    // Dons médicaux
    Route::get('/dons', [PatientDashboardController::class, 'dons'])->name('dons');

    // Routes API patient (JSON) - sous le même middleware auth
    // Médecins par spécialité
    Route::get('/medecins/by-specialite', [PatientRendezVousController::class, 'getMedecinsBySpecialite'])
        ->name('api.medecins.by-specialite');

    // Détails d'un médecin
    Route::get('/medecins/{medecin}', [PatientRendezVousController::class, 'getMedecinDetails'])
        ->name('api.medecin.details');

    // Créneaux disponibles d'un médecin
    Route::get('/medecins/{medecin}/creneaux-disponibles', [PatientRendezVousController::class, 'getCreneauxDisponibles'])
        ->name('api.medecin.creneaux-disponibles');
});

//payment
Route::post('/webhook/paymee', [PaiementController::class, 'handleWebhook']);
Route::get('/payment/success', [PaiementController::class, 'success'])->name('payment.success');
Route::get('payment/cancel/{rendezvousId}', [PaiementController::class, 'cancel'])->name('payment.cancel');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistiques', [App\Http\Controllers\Admin\AdminDashboardController::class, 'statistiques'])->name('statistiques');
    Route::get('/utilisateurs', [App\Http\Controllers\Admin\AdminDashboardController::class, 'utilisateurs'])->name('utilisateurs');
    Route::get('/rapports', [App\Http\Controllers\Admin\AdminDashboardController::class, 'rapports'])->name('rapports');

    // Gestion des médecins
    Route::get('/medecins', [GestionMedecinController::class, 'index'])->name('medecins.index');
    Route::get('/medecins/create', [GestionMedecinController::class, 'create'])->name('medecins.create');
    Route::post('/medecins', [GestionMedecinController::class, 'store'])->name('medecins.store');
    Route::get('/medecins/{medecin}/edit', [GestionMedecinController::class, 'edit'])->name('medecins.edit');
    Route::put('/medecins/{medecin}', [GestionMedecinController::class, 'update'])->name('medecins.update');
    Route::patch('/medecins/{medecin}/toggle-status', [GestionMedecinController::class, 'toggleStatus'])->name('medecins.toggle-status');
    Route::delete('/medecins/{medecin}', [GestionMedecinController::class, 'destroy'])->name('medecins.destroy');
    Route::get('/medecins/{medecin}', [GestionMedecinController::class, 'show'])->name('medecins.show');
});
