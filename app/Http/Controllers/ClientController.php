<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::with('vehicules')->orderBy('nom')->get();
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ], [
            'nom.required' => 'Le champ nom est obligatoire.',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères.',
        ]);

        try {
            Client::create([
                'nom' => $request->nom,
                'contact' => $request->contact ?: null,
                'note' => $request->note ?: null,
            ]);

            return redirect()->route('clients.index')
                ->with('success', 'Client créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du client : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::with('vehicules.sim')->findOrFail($id);
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ], [
            'nom.required' => 'Le champ nom est obligatoire.',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères.',
        ]);

        try {
            $client = Client::findOrFail($id);
            $client->update([
                'nom' => $request->nom,
                'contact' => $request->contact ?: null,
                'note' => $request->note ?: null,
            ]);

            return redirect()->route('clients.index')
                ->with('success', 'Client mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du client : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $client = Client::findOrFail($id);
            
            // Vérifier si le client a des véhicules
            if ($client->vehicules()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer un client ayant des véhicules. Supprimez d\'abord les véhicules associés.');
            }
            
            $client->delete();

            return redirect()->route('clients.index')
                ->with('success', 'Client supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression du client : ' . $e->getMessage());
        }
    }
}
