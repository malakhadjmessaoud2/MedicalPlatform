<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RendezVousController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes pour les médecins
Route::prefix('medecins')->group(function () {
    Route::get('/by-specialite', [RendezVousController::class, 'getMedecinsBySpecialite'])
        ->name('api.medecins.by.specialite');

    Route::get('/{medecin}/creneaux-disponibles', [RendezVousController::class, 'getCreneauxDisponibles'])
        ->name('api.medecin.creneaux-disponibles');

    Route::get('/{medecin}', [RendezVousController::class, 'getMedecinDetails'])
        ->name('api.medecin.details');
});
