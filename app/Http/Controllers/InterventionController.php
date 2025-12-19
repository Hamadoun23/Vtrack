<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Vehicule;
use Illuminate\Http\Request;

class InterventionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $vehicule_id)
    {
        $request->validate([
            'description' => 'required|string',
            'date_intervention' => 'required|date',
        ]);

        $vehicule = Vehicule::findOrFail($vehicule_id);
        
        Intervention::create([
            'vehicule_id' => $vehicule->id_vehicule,
            'description' => $request->description,
            'date_intervention' => $request->date_intervention,
        ]);

        return redirect()->route('vehicules.show', $vehicule->id_vehicule)
            ->with('success', 'Intervention ajoutée avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($vehicule_id, $intervention_id)
    {
        $intervention = Intervention::findOrFail($intervention_id);
        $vehicule_id = $intervention->vehicule_id;
        $intervention->delete();

        return redirect()->route('vehicules.show', $vehicule_id)
            ->with('success', 'Intervention supprimée avec succès.');
    }
}
