<?php

namespace App\Http\Controllers\Donateur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashDonateur.index');
    }
    public function dons()
    {
        return view('dashDonateur.dons.index');
    }
    public function nouveauDon()
    {
        return view('dashDonateur.dons.nouveau');
    }
    public function suivi()
    {
        return view('dashDonateur.suivi.index');
    }
    public function rapports()
    {
        return view('dashDonateur.rapports.index');
    }
    public function notifications()
    {
        return view('dashDonateur.notifications.index');
    }
    public function parametres()
    {
        return view('dashDonateur.parametres.index');
    }
    public function aides()
    {
        return view('dashDonateur.aide.index');
    }

}
