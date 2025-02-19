<?php

use App\Http\Controllers\PharmacieController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

Route::prefix('dashboard/medecin')->group(function () {
    // Dashboard principal médecin
    Route::get('/', function () {
        return view('dashMedecin.index');
    })->name('dashboard.medecin');

    // Gestion des patients
    Route::get('/patients', function () {
        return view('dashMedecin.gestionPatient.index');
    })->name('medecin.patients');
    // Gestion des dossier
    Route::get('/dossier', function () {
        return view('dashMedecin.gestionPatient.dossierMedical');
    })->name('medecin.dossiermedical');

    // Agenda & Rendez-vous
    Route::get('/agenda', function () {
        return view('dashMedecin.AgendaRendezvous.index');
    })->name('medecin.agenda');

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
});

Route::prefix('dashboard/patient')->group(function () {
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
