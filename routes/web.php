<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehiculeController;
use App\Http\Controllers\SimController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\InterventionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirection de la racine vers le dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Routes d'authentification (guest)
require __DIR__.'/auth.php';

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export-csv', [DashboardController::class, 'exportCsv'])->name('dashboard.exportCsv');

    // Recherche
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // Clients
    Route::resource('clients', ClientController::class);

    // Véhicules
    Route::resource('vehicules', VehiculeController::class);
    Route::post('/vehicules/{vehicule}/suspendre', [VehiculeController::class, 'suspendre'])->name('vehicules.suspendre');
    Route::post('/vehicules/{vehicule}/reactiver', [VehiculeController::class, 'reactiver'])->name('vehicules.reactiver');
    Route::post('/vehicules/{vehicule}/remplacer-sim', [VehiculeController::class, 'remplacerSim'])->name('vehicules.remplacerSim');

    // Interventions
    Route::post('/vehicules/{vehicule}/interventions', [InterventionController::class, 'store'])->name('interventions.store');
    Route::delete('/vehicules/{vehicule}/interventions/{intervention}', [InterventionController::class, 'destroy'])->name('interventions.destroy');

    // SIM
    Route::resource('sims', SimController::class);
    Route::post('/sims/{sim}/bloquer', [SimController::class, 'bloquer'])->name('sims.bloquer');
    Route::post('/sims/{sim}/debloquer', [SimController::class, 'debloquer'])->name('sims.debloquer');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
