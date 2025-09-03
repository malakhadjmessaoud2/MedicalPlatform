<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nom' => 'medecin1',
            'prenom' => 'medecin1',
            'email' => 'medecin1@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'medecin',
            'prixConsultation' => 100,
            'specialite' => 'Médecin de famille',
            'adresse_cabinet' => '123 Rue de la Paix, Paris, France',
            'experience' => 10,
            'formation' => 'Université de Paris',
            'langues' => 'Français, Anglais',
            'score' => 0,
            'tel' => '0606060606',

        ]);
        User::create([
            'nom' => 'medecin2',
            'prenom' => 'medecin2',
            'email' => 'medecin2@example.com',
            'password' => Hash::make('123456789'),
            'role' => 'medecin',
            'prixConsultation' => 100,
            'specialite' => 'Cardiologue',
            'adresse_cabinet' => '123 Rue de la Paix, Paris, France',
            'experience' => 10,
            'formation' => 'Université de Paris',
            'langues' => 'Français, Anglais',
            'score' => 0,
            'tel' => '0606060606',
        ]);
        User::create([
            'nom'=>'patient1',
            'prenom' => 'patient1',
            'email' => 'patient1@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'patient',
            'tel' => '0606060606',
            'adresse' => '123 Rue de la Paix, Paris, France',
        ]);
        User::create([
            'nom'=>'patient2',
            'prenom' => 'patient2',
            'email' => 'patient2@example.com',
            'password' => Hash::make('123456789'),
            'role' => 'patient',
            'tel' => '0606060606',
            'adresse' => '123 Rue de la Paix, Paris, France',
        ]);
        User::create([
            'nom' => 'admin',
            'prenom' => 'admin',
            'email' => 'malakhmm2@gmail.com',
            'password' => Hash::make('123456789'),
            'role' => 'admin',
        ]);
    }
}
