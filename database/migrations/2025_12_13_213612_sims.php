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
        Schema::create('sims', function (Blueprint $table) {
            $table->id('id_sim');
            $table->string('iccid')->nullable()->unique();
            $table->string('last5', 5)->unique();
            $table->string('numero')->nullable()->unique();
            $table->string('operateur')->nullable();
            $table->enum('statut', ['active', 'inactive', 'bloquee'])->default('active');
            $table->text('raison_blocage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sims');
    }
};
