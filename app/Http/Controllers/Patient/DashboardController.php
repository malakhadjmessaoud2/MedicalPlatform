<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashPatient.index');
    }
    public function createRendezVous()
    {
        return view('dashPatient.RendezVous.create');
    }

    public function dossierPatient()
    {
        return view('dashPatient.dossier.index');
    }

    public function messages()
    {
        return view('dashPatient.messages.index');
    }

    public function achatMedicament()
    {
        return view('dashPatient.achatMedicament.index');
    }
    public function commandes()
    {
        return view('dashPatient.commandes.index');
    }

    public function dons()
    {
        return view('dashPatient.dons.index');
    }

}
