<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration

{
    public function up(): void
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('medecin_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->enum('type', ['consultation', 'examen', 'intervention', 'autre'])->default('consultation');
            $table->text('description')->nullable();
            $table->enum('statut', ['pending', 'confirmed', 'payed', 'cancelled', 'rejected', 'completed'])->default('pending');
            $table->string('lien_en_ligne')->nullable();
            $table->string('payment_token')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rendez_vous');
    }
};
