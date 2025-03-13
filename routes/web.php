<?php

use App\Http\Controllers\PharmacieController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RendezVousController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;



Route::get('/', function () {
    return view('welcome');
});

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
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::prefix('dashboard/pharmacie')->group(function () {
    //dashboard pharmacie
    Route::get('/', function () {
        return view('dashPharmacie.index');
    })->name('dashboard.pharmacie');

    Route::get('/stock', function () {
        return view('dashPharmacie.stock.index');
    })->name('dashPharmacie.stock');

    Route::get('/commandes', function () {
        return view('dashPharmacie.commandes.index');
    })->name('dashPharmacie.commandes');

    Route::get('/commandes/validation', function () {
        return view('dashPharmacie.commandes.validationCommande');
    })->name('dashPharmacie.validationCommande');

    Route::get('/dons', function () {
        return view('dashPharmacie.donsReçus.index');
    })->name('dashPharmacie.donsReçus');

    Route::get('/donateurs', function () {
        return view('dashPharmacie.donateurs.index');
    })->name('dashPharmacie.donateurs');

    Route::get('/demandes-dons', function () {
        return view('dashPharmacie.demandesDons.index');
    })->name('dashPharmacie.demandesDons');
});
// Routes pour le dashboard donateur
Route::prefix('dashboard/donateur')->group(function () {
    // Dashboard principal donateur
    Route::get('/', function () {
        return view('dashDonateur.index');
    })->name('donateur.dashboard');

    // Gestion des dons
    Route::get('/mes-dons', function () {
        return view('dashDonateur.dons.index');
    })->name('donateur.dons');

    // Nouveau don
    Route::get('/nouveau-don', function () {
        return view('dashDonateur.dons.nouveau');
    })->name('donateur.nouveau-don');

    // Suivi des dons
    Route::get('/suivi', function () {
        return view('dashDonateur.suivi.index');
    })->name('donateur.suivi');

    // Rapports et statistiques
    Route::get('/rapports', function () {
        return view('dashDonateur.rapports.index');
    })->name('donateur.rapports');

    // Notifications
    Route::get('/notifications', function () {
        return view('dashDonateur.notifications.index');
    })->name('donateur.notifications');

    // Paramètres du compte
    Route::get('/parametres', function () {
        return view('dashDonateur.parametres.index');
    })->name('donateur.parametres');

    // Aide et support
    Route::get('/aide', function () {
        return view('dashDonateur.aide.index');
    })->name('donateur.aide');
});
Route::prefix('dashboard/medecin')->middleware(['auth', 'role:medecin'])->group(function () {

    Route::resource('rendez-vous', RendezVousController::class);
    // Dashboard principal médecin
    Route::get('/', function () {
        return view('dashMedecin.index');
    })->name('dashboard.medecin');

    // Gestion des patients
    Route::get('/patients', function () {
        return view('dashMedecin.gestionPatient.index');
    })->name('medecin.patients');

    // Gestion des dossiers
    Route::get('/dossier', function () {
        return view('dashMedecin.gestionPatient.dossierMedical');
    })->name('medecin.dossiermedical');

    // Agenda & Rendez-vous
    Route::controller(RendezVousController::class)->group(function () {
        Route::get('/agenda', 'index')->name('medecin.agenda');
        Route::get('/rendez-vous/evenements', 'getEvenements')->name('rendez-vous.evenements');
        Route::post('/rendez-vous', 'store')->name('rendez-vous.store');
        Route::put('/rendez-vous/{rendezVous}', 'update')->name('rendez-vous.update');
        Route::delete('/rendez-vous/{rendezVous}', 'destroy')->name('rendez-vous.destroy');
    });

    // Traitements & Suivis
    Route::get('/traitements', function () {
        return view('dashMedecin.SuiviTraitements.index');
    })->name('medecin.traitements');

    // Gestion des Prestations
    Route::get('/prestations', function () {
        return view('dashMedecin.gestionPrestations.index');
    })->name('medecin.prestations');

    // Communication & Assistance
    Route::get('/communication', function () {
        return view('dashMedecin.communication.index');
    })->name('medecin.communication');

    Route::get('/patients/search', [RendezVousController::class, 'getPatients'])->name('patients.search');
    // Routes pour les rendez-vous
    Route::get('/rendez-vous', [RendezVousController::class, 'index'])->name('medecin.rendez-vous.index');

    // API pour les opérations CRUD sur les rendez-vous
    Route::get('/api/rendez-vous', [RendezVousController::class, 'getEvenements'])->name('medecin.rendez-vous.evenements');
    Route::post('/api/rendez-vous', [RendezVousController::class, 'store'])->name('medecin.rendez-vous.store');
    Route::get('/api/rendez-vous/{rendezVous}', [RendezVousController::class, 'show'])->name('medecin.rendez-vous.show');
    Route::put('/api/rendez-vous/{rendezVous}', [RendezVousController::class, 'update'])->name('medecin.rendez-vous.update');
    Route::delete('/api/rendez-vous/{rendezVous}', [RendezVousController::class, 'destroy'])->name('medecin.rendez-vous.destroy');

    // Ajouter cette route pour gérer le changement de statut
    Route::put('/api/rendez-vous/{rendezVous}/statut', [RendezVousController::class, 'updateStatus'])->name('medecin.rendez-vous.updateStatus');

    // API pour récupérer la liste des patients
    Route::get('/api/patients', [RendezVousController::class, 'getPatients'])->name('medecin.patients.list');
});

Route::prefix('dashboard/patient')->middleware(['auth', 'role:patient'])->group(function () {
    // Dashboard patient
    Route::get('/', function () {
        return view('dashPatient.index');
    })->name('dashboard.patient');

    // Achat médicaments
    Route::get('/medicaments', function () {
        return view('dashPatient.achatMedicament.index');
    })->name('patient.medicaments');

    // Commandes
    Route::get('/commandes', function () {
        return view('dashPatient.commandes.index');
    })->name('patient.commandes');

    // Dons médicaux
    Route::get('/dons', function () {
        return view('dashPatient.dons.index');
    })->name('patient.dons');
});


