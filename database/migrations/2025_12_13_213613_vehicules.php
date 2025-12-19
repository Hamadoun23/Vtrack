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
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id('id_vehicule');
            $table->string('immatriculation')->unique();
            $table->string('marque_modele')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id_client')->on('clients')->onDelete('cascade');
            $table->unsignedBigInteger('sim_id')->nullable()->unique();
            $table->foreign('sim_id')->references('id_sim')->on('sims')->onDelete('set null');
            $table->enum('statut', ['actif', 'suspendu'])->default('actif');
            $table->text('raison_suspension')->nullable();
            $table->timestamps();
        });

        Schema::create('interventions', function (Blueprint $table) {
            $table->id('id_intervention');
            $table->unsignedBigInteger('vehicule_id');
            $table->foreign('vehicule_id')->references('id_vehicule')->on('vehicules')->onDelete('cascade');
            $table->text('description');
            $table->date('date_intervention');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
        Schema::dropIfExists('vehicules');
    }
};
