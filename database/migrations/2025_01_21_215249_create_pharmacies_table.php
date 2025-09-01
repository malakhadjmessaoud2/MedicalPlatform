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
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('operateurpharmacie_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nom');
            $table->string('localisation');
            $table->string('tel');
            $table->string('email')->nullable();
            $table->string('site_web')->nullable();
            $table->string('horaires')->nullable();
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacies');
    }
};
