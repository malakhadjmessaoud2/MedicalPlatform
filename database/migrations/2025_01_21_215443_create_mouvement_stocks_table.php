<?php

use App\Models\Pharmacie;
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
        Schema::create('mouvement_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('pharmacie_id')->nullable()->constrained('pharmacies')->onDelete('cascade');
            $table->foreignId('medicament_id')->nullable()->constrained('medicaments')->onDelete('cascade');
            $table->integer('quantite')->nullable();
            $table->date('dateMov')->nullable();
            $table->date('dateExpiration')->nullable();
            $table->integer('prix')->nullable();
            $table->enum('type', ['entree','sortie'])->default('sortie');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement_stocks');
    }
};
