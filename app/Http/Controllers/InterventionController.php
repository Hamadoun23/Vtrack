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
        ], [
            'description.required' => 'Le champ description est obligatoire.',
            'date_intervention.required' => 'Le champ date d\'intervention est obligatoire.',
            'date_intervention.date' => 'La date d\'intervention doit être une date valide.',
        ]);

        try {
            $vehicule = Vehicule::findOrFail($vehicule_id);
            
            Intervention::create([
                'vehicule_id' => $vehicule->id_vehicule,
                'description' => $request->description,
                'date_intervention' => $request->date_intervention,
            ]);

            return redirect()->route('vehicules.show', $vehicule->id_vehicule)
                ->with('success', 'Intervention ajoutée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'ajout de l\'intervention : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($vehicule, $intervention)
    {
        try {
            $intervention = Intervention::findOrFail($intervention);
            $vehicule_id = $intervention->vehicule_id;
            $intervention->delete();

            return redirect()->route('vehicules.show', $vehicule_id)
                ->with('success', 'Intervention supprimée avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression de l\'intervention : ' . $e->getMessage());
        }
    }
}
