<?php

namespace App\Http\Controllers;

use App\Models\Sim;
use Illuminate\Http\Request;
use Illuminate\Database\UniqueConstraintViolationException;

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
            'last5' => 'required|string|max:5|unique:sims',
            'numero' => 'nullable|string|max:255|unique:sims',
            'operateur' => 'nullable|string|max:255',
            'statut' => 'required|in:active,inactive,bloquee',
            'raison_blocage' => 'nullable|required_if:statut,bloquee|string',
        ], [
            'last5.required' => 'Le champ last5 est obligatoire.',
            'last5.unique' => 'Le last5 est déjà utilisé.',
            'last5.max' => 'Le last5 ne doit pas dépasser 5 caractères.',
            'numero.unique' => 'Le numéro est déjà utilisé.',
            'iccid.unique' => 'L\'ICCID est déjà utilisé.',
            'statut.required' => 'Le champ statut est obligatoire.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
            'raison_blocage.required_if' => 'La raison de blocage est obligatoire lorsque le statut est "bloquée".',
        ]);

        try {
            Sim::create($request->all());

            return redirect()->route('sims.index')
                ->with('success', 'SIM créée avec succès.');
        } catch (UniqueConstraintViolationException $e) {
            $errorMessage = $e->getMessage();
            $message = 'Une erreur est survenue lors de la création de la SIM.';
            
            if (str_contains($errorMessage, 'sims_last5_unique') || str_contains($errorMessage, 'last5')) {
                $message = 'Le last5 "' . $request->last5 . '" est déjà utilisé. Veuillez choisir un autre last5.';
            } elseif (str_contains($errorMessage, 'sims_numero_unique') || str_contains($errorMessage, 'numero')) {
                $message = 'Le numéro "' . $request->numero . '" est déjà utilisé. Veuillez choisir un autre numéro.';
            } elseif (str_contains($errorMessage, 'sims_iccid_unique') || str_contains($errorMessage, 'iccid')) {
                $message = 'L\'ICCID "' . $request->iccid . '" est déjà utilisé. Veuillez choisir un autre ICCID.';
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $message);
        }
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
            'last5' => 'required|string|max:5|unique:sims,last5,' . $id . ',id_sim',
            'numero' => 'nullable|string|max:255|unique:sims,numero,' . $id . ',id_sim',
            'operateur' => 'nullable|string|max:255',
            'statut' => 'required|in:active,inactive,bloquee',
            'raison_blocage' => 'nullable|required_if:statut,bloquee|string',
        ], [
            'last5.required' => 'Le champ last5 est obligatoire.',
            'last5.unique' => 'Le last5 est déjà utilisé.',
            'last5.max' => 'Le last5 ne doit pas dépasser 5 caractères.',
            'numero.unique' => 'Le numéro est déjà utilisé.',
            'iccid.unique' => 'L\'ICCID est déjà utilisé.',
            'statut.required' => 'Le champ statut est obligatoire.',
            'statut.in' => 'Le statut sélectionné n\'est pas valide.',
            'raison_blocage.required_if' => 'La raison de blocage est obligatoire lorsque le statut est "bloquée".',
        ]);

        try {
            $sim = Sim::findOrFail($id);
            $sim->update($request->all());

            return redirect()->route('sims.index')
                ->with('success', 'SIM mise à jour avec succès.');
        } catch (UniqueConstraintViolationException $e) {
            $errorMessage = $e->getMessage();
            $message = 'Une erreur est survenue lors de la mise à jour de la SIM.';
            
            if (str_contains($errorMessage, 'sims_last5_unique') || str_contains($errorMessage, 'last5')) {
                $message = 'Le last5 "' . $request->last5 . '" est déjà utilisé. Veuillez choisir un autre last5.';
            } elseif (str_contains($errorMessage, 'sims_numero_unique') || str_contains($errorMessage, 'numero')) {
                $message = 'Le numéro "' . $request->numero . '" est déjà utilisé. Veuillez choisir un autre numéro.';
            } elseif (str_contains($errorMessage, 'sims_iccid_unique') || str_contains($errorMessage, 'iccid')) {
                $message = 'L\'ICCID "' . $request->iccid . '" est déjà utilisé. Veuillez choisir un autre ICCID.';
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $message);
        }
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
        ], [
            'raison_blocage.required' => 'La raison de blocage est obligatoire.',
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
