<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Renommer la colonne et modifier sa longueur
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE sims CHANGE last4 last5 VARCHAR(5) NOT NULL');
        } else {
            // Pour SQLite et autres SGBD
            Schema::table('sims', function (Blueprint $table) {
                $table->string('last5', 5)->after('iccid');
            });
            
            DB::statement('UPDATE sims SET last5 = last4');
            
            Schema::table('sims', function (Blueprint $table) {
                $table->dropColumn('last4');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Renommer la colonne et modifier sa longueur
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE sims CHANGE last5 last4 VARCHAR(4) NOT NULL');
        } else {
            // Pour SQLite et autres SGBD
            Schema::table('sims', function (Blueprint $table) {
                $table->string('last4', 4)->after('iccid');
            });
            
            DB::statement('UPDATE sims SET last4 = last5');
            
            Schema::table('sims', function (Blueprint $table) {
                $table->dropColumn('last5');
            });
        }
    }
};
