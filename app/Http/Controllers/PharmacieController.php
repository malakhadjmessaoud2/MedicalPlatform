<?php

namespace App\Http\Controllers;

abstract class PharmacieController
{
public function dashboard()
{
    return view('dashPharmacie.index');
}

public function stock()
{
    return view('dashPharmacie.stock.index');
}

public function commandes()
{
    return view('dashPharmacie.commandes.index');
}

public function dons()
{
    return view('dashPharmacie.dons.index');
}

public function donateurs()
{
    return view('dashPharmacie.donateurs.index');
}

public function demandesDons()
{
    return view('dashPharmacie.demandes-dons.index');
}
}
