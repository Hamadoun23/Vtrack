<?php

namespace App\Http\Controllers;

use App\Models\Sim;
use Illuminate\Http\Request;

class SimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sims = Sim::with('vehicule.client')->orderBy('iccid')->get();
        return view('sims.index', compact('sims'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sims.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'iccid' => 'nullable|string|max:255|unique:sims',
            'last5' => 'required|string|max:5',
            'numero' => 'nullable|string|max:255',
            'operateur' => 'nullable|string|max:255',
            'statut' => 'required|in:active,inactive,bloquee',
            'raison_blocage' => 'nullable|required_if:statut,bloquee|string',
        ]);

        Sim::create($request->all());

        return redirect()->route('sims.index')
            ->with('success', 'SIM créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sim = Sim::with('vehicule.client')->findOrFail($id);
        return view('sims.show', compact('sim'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sim = Sim::findOrFail($id);
        return view('sims.edit', compact('sim'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'iccid' => 'nullable|string|max:255|unique:sims,iccid,' . $id . ',id_sim',
            'last5' => 'required|string|max:5',
            'numero' => 'nullable|string|max:255',
            'operateur' => 'nullable|string|max:255',
            'statut' => 'required|in:active,inactive,bloquee',
            'raison_blocage' => 'nullable|required_if:statut,bloquee|string',
        ]);

        $sim = Sim::findOrFail($id);
        $sim->update($request->all());

        return redirect()->route('sims.index')
            ->with('success', 'SIM mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sim = Sim::findOrFail($id);
        
        // Vérifier si la SIM est utilisée par un véhicule
        if ($sim->vehicule) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer une SIM associée à un véhicule.');
        }

        $sim->delete();

        return redirect()->route('sims.index')
            ->with('success', 'SIM supprimée avec succès.');
    }

    /**
     * Bloquer une SIM
     */
    public function bloquer(Request $request, string $id)
    {
        $request->validate([
            'raison_blocage' => 'required|string',
        ]);

        $sim = Sim::findOrFail($id);
        $sim->update([
            'statut' => 'bloquee',
            'raison_blocage' => $request->raison_blocage,
        ]);

        return redirect()->back()
            ->with('success', 'SIM bloquée avec succès.');
    }

    /**
     * Débloquer une SIM
     */
    public function debloquer(string $id)
    {
        $sim = Sim::findOrFail($id);
        $sim->update([
            'statut' => 'active',
            'raison_blocage' => null,
        ]);

        return redirect()->back()
            ->with('success', 'SIM débloquée avec succès.');
    }
}
