<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountActivedMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GestionMedecinController extends Controller
{
    public function index()
    {
        // Récupérer la liste des médecins
        $medecins = User::where('role', 'medecin')->get();

        return view('dashAdmin.medecins.index', compact('medecins'));
    }

    public function create()
    {
        return view('dashAdmin.medecins.create');
    }

    public function show(User $medecin)
    {
        return view('dashAdmin.medecins.show', compact('medecin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'specialite' => 'nullable|string|max:255',
            'prix_consultation' => 'nullable|numeric|min:0',
            'adresse_cabinet' => 'nullable|string|max:500',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'medecin',
            'specialite' => $request->specialite,
            'prix_consultation' => $request->prix_consultation,
            'adresse_cabinet' => $request->adresse_cabinet,
        ]);

        return redirect()->route('admin.medecins.index')->with('success', 'Médecin ajouté avec succès.');
    }

    public function edit(User $medecin)
    {
        return view('dashAdmin.medecins.edit', compact('medecin'));
    }

    public function update(Request $request, User $medecin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $medecin->id,
            'specialite' => 'nullable|string|max:255',
            'prix_consultation' => 'nullable|numeric|min:0',
            'adresse_cabinet' => 'nullable|string|max:500',
        ]);

        $medecin->update([
            'name' => $request->name,
            'email' => $request->email,
            'specialite' => $request->specialite,
            'prix_consultation' => $request->prix_consultation,
            'adresse_cabinet' => $request->adresse_cabinet,
        ]);

        return redirect()->route('admin.medecins.index')->with('success', 'Médecin mis à jour avec succès.');
    }

    public function destroy(User $medecin)
    {
        $medecin->delete();

        return redirect()->route('admin.medecins.index')->with('success', 'Médecin supprimé avec succès.');
    }

    public function toggleStatus(User $medecin)
    {
        $medecin->isActive = !$medecin->isActive;
        $medecin->save();

        if ($medecin->isActive) {
            $medecin->email_verified_at = now();
            $medecin->save();

            // Envoyer un email de notification d'activation
            Mail::to($medecin->email)->send(new AccountActivedMail($medecin));
        }

        return back()->with('success', 'Statut du compte mis à jour.');
    }
}

