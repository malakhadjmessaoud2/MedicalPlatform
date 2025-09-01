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
        Schema::create('paiements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('commande_id')->nullable()->constrained('commandes')->onDelete('cascade');
            $table->foreignId('consultation_id')->nullable()->constrained('consultations')->onDelete('cascade');
            $table->foreignId('demandeDons_id')->nullable()->constrained('demande_dons')->onDelete('cascade');
            $table->float('montant');
            $table->enum('type', ['don', 'consultation', 'commande'])->default('consultation');
            $table->date('datePaiement');
            $table->foreignId('don_id')->nullable()->constrained('demande_dons')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
