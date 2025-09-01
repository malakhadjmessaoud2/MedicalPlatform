<?php

namespace App\Http\Controllers\Pharmacie;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashPharmacie.index');
    }

    public function stocks(){
        return view('dashPharmacie.stock.index');
    }

    public function commandes(){
        return view('dashPharmacie.commandes.index');
    }
    public function validationCommande(){
        return view('dashPharmacie.commandes.validationCommande');
    }

    public function donsReçus(){
        return view('dashPharmacie.donsReçus.index');
    }
    public function donateurs(){
        return view('dashPharmacie.donateurs.index');
    }
    public function demandesDons(){
        return view('dashPharmacie.demandesDons.index');
    }

}
