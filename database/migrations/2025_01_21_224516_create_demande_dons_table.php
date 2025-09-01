<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('demande_dons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('pharmacie_id')->nullable()->constrained('pharmacies')->onDelete('cascade');
            $table->foreignId('donateur_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->integer('quantite_demandee');
            $table->date('date_demande');
            $table->enum('status', ['en_attente', 'accepté', 'payé', 'refusé'])->default('en_attente');
            $table->foreignId('certification_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_dons');
    }
};
