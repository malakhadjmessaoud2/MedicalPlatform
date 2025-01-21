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
        Schema::create('paiement_dons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donateur_id')->constrained()->onDelete('cascade');
            $table->foreignId('pharmacie_id')->constrained()->onDelete('cascade');
            $table->float('montant');
            $table->date('date_paiement');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiement_dons');
    }
};
