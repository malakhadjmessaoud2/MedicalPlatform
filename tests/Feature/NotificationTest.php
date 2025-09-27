<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RendezVous;
use App\Notifications\RendezVousCreatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_creation()
    {
        // Créer un utilisateur médecin
        $medecin = User::factory()->create([
            'role' => 'medecin',
            'nom' => 'Dr. Test',
            'prenom' => 'Médecin'
        ]);

        // Créer un utilisateur patient
        $patient = User::factory()->create([
            'role' => 'patient',
            'nom' => 'Patient',
            'prenom' => 'Test'
        ]);

        // Créer un rendez-vous
        $rendezVous = RendezVous::create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'date_debut' => now()->addHour(),
            'date_fin' => now()->addHour()->addMinutes(30),
            'type' => 'consultation',
            'description' => 'Test de notification',
            'statut' => 'pending'
        ]);

        // Envoyer une notification
        $medecin->notify(new RendezVousCreatedNotification($rendezVous));

        // Vérifier que la notification a été créée
        $this->assertDatabaseHas('notifications', [
            'notifiable_type' => User::class,
            'notifiable_id' => $medecin->id,
            'type' => RendezVousCreatedNotification::class,
        ]);

        // Vérifier que le médecin a une notification
        $this->assertEquals(1, $medecin->notifications()->count());
        $this->assertEquals(1, $medecin->unreadNotifications()->count());
    }

    public function test_notification_api_endpoints()
    {
        $user = User::factory()->create(['role' => 'medecin']);

        // Tester l'endpoint des notifications récentes
        $response = $this->actingAs($user)->get('/notifications/recent');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'notifications',
            'unread_count'
        ]);

        // Tester l'endpoint du compteur de notifications non lues
        $response = $this->actingAs($user)->get('/notifications/unread-count');
        $response->assertStatus(200);
        $response->assertJsonStructure(['unread_count']);
    }
}
