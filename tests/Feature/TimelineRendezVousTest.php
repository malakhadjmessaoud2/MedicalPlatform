<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;

class TimelineRendezVousTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $medecin;
    protected $patient;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un médecin
        $user = User::factory()->create(['role' => 'medecin']);
        $this->medecin = Medecin::factory()->create(['user_id' => $user->id]);

        // Créer un patient
        $patientUser = User::factory()->create(['role' => 'patient']);
        $this->patient = Patient::factory()->create(['user_id' => $patientUser->id]);
    }

    /**
     * Test de l'API des rendez-vous du jour
     */
    public function test_api_rendez_vous_du_jour()
    {
        $user = $this->medecin->user;

        // Créer des rendez-vous pour aujourd'hui
        $rdv1 = RendezVous::factory()->create([
            'medecin_id' => $this->medecin->id,
            'patient_id' => $this->patient->id,
            'date_debut' => Carbon::today()->addHours(9), // 9h00
            'date_fin' => Carbon::today()->addHours(9)->addMinutes(30),
            'statut' => 'confirmé'
        ]);

        $rdv2 = RendezVous::factory()->create([
            'medecin_id' => $this->medecin->id,
            'patient_id' => $this->patient->id,
            'date_debut' => Carbon::today()->addHours(14), // 14h00
            'date_fin' => Carbon::today()->addHours(14)->addMinutes(30),
            'statut' => 'confirmé'
        ]);

        $response = $this->actingAs($user)
            ->get('/medecin/api/rendez-vous-du-jour');

        $response->assertStatus(200);
        $response->assertJsonCount(2);

        $data = $response->json();
        $this->assertEquals($rdv1->id, $data[0]['id']);
        $this->assertEquals($rdv2->id, $data[1]['id']);
    }

    /**
     * Test de l'affichage de la timeline dans la navbar
     */
    public function test_timeline_display_in_navbar()
    {
        $user = $this->medecin->user;

        // Créer un rendez-vous en cours
        $rdvEnCours = RendezVous::factory()->create([
            'medecin_id' => $this->medecin->id,
            'patient_id' => $this->patient->id,
            'date_debut' => Carbon::now()->subMinutes(15),
            'date_fin' => Carbon::now()->addMinutes(15),
            'statut' => 'confirmé'
        ]);

        // Créer un rendez-vous à venir
        $rdvAVenir = RendezVous::factory()->create([
            'medecin_id' => $this->medecin->id,
            'patient_id' => $this->patient->id,
            'date_debut' => Carbon::now()->addHours(2),
            'date_fin' => Carbon::now()->addHours(2)->addMinutes(30),
            'statut' => 'confirmé'
        ]);

        $response = $this->actingAs($user)
            ->get('/medecin');

        $response->assertStatus(200);
        $response->assertSee('RDV du jour');
        $response->assertSee('En cours:');
        $response->assertSee('Prochain:');
    }

    /**
     * Test de la route de test des redirections
     */
    public function test_test_redirects_route()
    {
        $user = $this->medecin->user;

        $response = $this->actingAs($user)
            ->get('/test-redirects');

        $response->assertStatus(200);
        $response->assertJson([
            'current_user' => [
                'id' => $user->id,
                'role' => 'medecin',
                'name' => $user->name
            ],
            'expected_redirect' => '/medecin'
        ]);
    }

    /**
     * Test de la route de test du statut d'authentification
     */
    public function test_test_auth_status_route()
    {
        $user = $this->medecin->user;

        $response = $this->actingAs($user)
            ->get('/test-auth-status');

        $response->assertStatus(200);
        $response->assertJson([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'role' => 'medecin',
                'has_medecin' => true
            ]
        ]);
    }
}

