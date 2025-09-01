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
        Schema::create('documents_medecaux', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('dossier_medical_id')->constrained('dossier_medicals')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_medecaux');
    }
};
