<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use App\Models\Client;
use App\Models\Sim;
use Illuminate\Http\Request;

class VehiculeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicules = Vehicule::with(['client', 'sim'])->orderBy('immatriculation')->get();
        return view('vehicules.index', compact('vehicules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::orderBy('nom')->get();
        $sims = Sim::where('statut', 'active')->whereDoesntHave('vehicule')->get();
        return view('vehicules.create', compact('clients', 'sims'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'immatriculation' => 'required|string|max:255|unique:vehicules',
            'client_id' => 'nullable|exists:clients,id_client',
            'sim_id' => 'nullable|exists:sims,id_sim',
            'statut' => 'required|in:actif,suspendu',
            'raison_suspension' => 'nullable|required_if:statut,suspendu|string',
        ], [
            'immatriculation.required' => 'Le champ immatriculation est obligatoire.',
            'immatriculation.unique' => 'L\'immatriculation est déjà utilisée.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'sim_id.exists' => 'La SIM sélectionnée n\'existe pas.',
            'statut.required' => 'Le champ statut est obligatoire.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
            'raison_suspension.required_if' => 'La raison de suspension est obligatoire lorsque le statut est "suspendu".',
        ]);

        Vehicule::create($request->all());

        return redirect()->route('vehicules.index')
            ->with('success', 'Véhicule créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $vehicule = Vehicule::with(['client', 'sim', 'interventions'])->findOrFail($id);
        $simsDisponibles = Sim::where('statut', 'active')
            ->where(function($query) use ($vehicule) {
                $query->whereDoesntHave('vehicule')
                      ->orWhere('id_sim', $vehicule->sim_id);
            })
            ->get();
        return view('vehicules.show', compact('vehicule', 'simsDisponibles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $vehicule = Vehicule::findOrFail($id);
        $clients = Client::orderBy('nom')->get();
        $sims = Sim::where('statut', 'active')
            ->where(function($query) use ($vehicule) {
                $query->whereDoesntHave('vehicule')
                      ->orWhere('id_sim', $vehicule->sim_id);
            })
            ->get();
        return view('vehicules.edit', compact('vehicule', 'clients', 'sims'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'immatriculation' => 'required|string|max:255|unique:vehicules,immatriculation,' . $id . ',id_vehicule',
            'client_id' => 'nullable|exists:clients,id_client',
            'sim_id' => 'nullable|exists:sims,id_sim',
            'statut' => 'required|in:actif,suspendu',
            'raison_suspension' => 'nullable|required_if:statut,suspendu|string',
        ], [
            'immatriculation.required' => 'Le champ immatriculation est obligatoire.',
            'immatriculation.unique' => 'L\'immatriculation est déjà utilisée.',
            'client_id.exists' => 'Le client sélectionné n\'existe pas.',
            'sim_id.exists' => 'La SIM sélectionnée n\'existe pas.',
            'statut.required' => 'Le champ statut est obligatoire.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
            'raison_suspension.required_if' => 'La raison de suspension est obligatoire lorsque le statut est "suspendu".',
        ]);

        $vehicule = Vehicule::findOrFail($id);
        $vehicule->update($request->all());

        return redirect()->route('vehicules.index')
            ->with('success', 'Véhicule mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vehicule = Vehicule::findOrFail($id);
        $vehicule->delete();

        return redirect()->route('vehicules.index')
            ->with('success', 'Véhicule supprimé avec succès.');
    }

    /**
     * Suspendre un véhicule
     */
    public function suspendre(Request $request, string $id)
    {
        $request->validate([
            'raison_suspension' => 'required|string',
        ], [
            'raison_suspension.required' => 'La raison de suspension est obligatoire.',
        ]);

        $vehicule = Vehicule::findOrFail($id);
        $vehicule->update([
            'statut' => 'suspendu',
            'raison_suspension' => $request->raison_suspension,
        ]);

        return redirect()->back()
            ->with('success', 'Véhicule suspendu avec succès.');
    }

    /**
     * Réactiver un véhicule
     */
    public function reactiver(string $id)
    {
        $vehicule = Vehicule::findOrFail($id);
        $vehicule->update([
            'statut' => 'actif',
            'raison_suspension' => null,
        ]);

        return redirect()->back()
            ->with('success', 'Véhicule réactivé avec succès.');
    }

    /**
     * Remplacer la SIM d'un véhicule
     */
    public function remplacerSim(Request $request, string $id)
    {
        $request->validate([
            'nouvelle_sim_id' => 'required|exists:sims,id_sim',
        ], [
            'nouvelle_sim_id.required' => 'La nouvelle SIM est obligatoire.',
            'nouvelle_sim_id.exists' => 'La SIM sélectionnée n\'existe pas.',
        ]);

        $vehicule = Vehicule::findOrFail($id);
        
        // Mettre l'ancienne SIM inactive si elle existe
        if ($vehicule->sim_id) {
            $ancienneSim = Sim::find($vehicule->sim_id);
            if ($ancienneSim) {
                $ancienneSim->update(['statut' => 'inactive']);
            }
        }

        // Assigner la nouvelle SIM
        $nouvelleSim = Sim::findOrFail($request->nouvelle_sim_id);
        $nouvelleSim->update(['statut' => 'active']);
        
        $vehicule->update(['sim_id' => $request->nouvelle_sim_id]);

        return redirect()->back()
            ->with('success', 'SIM remplacée avec succès.');
    }
}
