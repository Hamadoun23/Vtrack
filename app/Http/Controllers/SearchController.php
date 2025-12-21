<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vehicule;
use App\Models\Sim;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Recherche rapide dans clients, véhicules et SIM
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (empty($query)) {
            return view('search.results', ['results' => []]);
        }

        $results = [];

        // ===== RECHERCHE DES CLIENTS =====
        // Recherche par nom ou contact (numéro de téléphone)
        $clients = Client::where(function($q) use ($query) {
                $q->where('nom', 'like', '%' . $query . '%')
                  ->orWhere('contact', 'like', '%' . $query . '%');
            })
            ->get();

        foreach ($clients as $client) {
            $nbVehicules = $client->vehicules()->count();
            $results[] = [
                'type' => 'client',
                'type_badge' => 'info',
                'titre' => $client->nom,
                'sous_titre' => $client->contact,
                'info_supplementaire' => $nbVehicules . ' véhicule(s)',
                'client' => $client->nom,
                'sim' => '-',
                'operateur' => '-',
                'statut' => null,
                'url' => route('clients.show', $client->id_client),
            ];
        }

        // ===== RECHERCHE DES VÉHICULES =====
        // Recherche par immatriculation
        $vehicules = Vehicule::with(['client', 'sim'])
            ->where('immatriculation', 'like', '%' . $query . '%')
            ->get();

        foreach ($vehicules as $vehicule) {
            $results[] = [
                'type' => 'véhicule',
                'type_badge' => 'success',
                'titre' => $vehicule->immatriculation,
                'sous_titre' => $vehicule->client->nom ?? 'Aucun client',
                'info_supplementaire' => $vehicule->client->nom ?? 'Aucun client',
                'client' => $vehicule->client->nom ?? 'N/A',
                'sim' => $vehicule->sim->numero ?? ($vehicule->sim->last5 ?? 'Aucune'),
                'operateur' => $vehicule->sim->operateur ?? 'N/A',
                'statut' => $vehicule->statut,
                'url' => route('vehicules.show', $vehicule->id_vehicule),
            ];
        }

        // ===== RECHERCHE DES SIM =====
        // Recherche par last5, iccid, numero ou operateur
        $sims = Sim::with(['vehicule.client'])
            ->where(function($q) use ($query) {
                $q->where('last5', 'like', $query . '%')  // last5 commence par la requête (prioritaire)
                  ->orWhere('iccid', 'like', $query . '%')  // ICCID commence par la requête
                  ->orWhere('last5', 'like', '%' . $query . '%')  // last5 contient la requête (fallback)
                  ->orWhere('iccid', 'like', '%' . $query . '%')  // ICCID contient la requête (fallback)
                  ->orWhere('numero', 'like', '%' . $query . '%')  // Numéro contient la requête
                  ->orWhere('operateur', 'like', '%' . $query . '%');  // Opérateur contient la requête
            })
            ->get();

        foreach ($sims as $sim) {
            $displaySim = $sim->numero ?? $sim->last5;
            $vehicule = $sim->vehicule;
            $client = $vehicule ? $vehicule->client : null;
            
            $results[] = [
                'type' => 'sim',
                'type_badge' => 'warning',
                'titre' => 'SIM ' . $sim->last5,
                'sous_titre' => ($sim->iccid ? 'ICCID: ' . $sim->iccid : '') . ($sim->operateur ? ' | ' . $sim->operateur : ''),
                'info_supplementaire' => $vehicule ? 'Véhicule: ' . $vehicule->immatriculation : 'Non assignée',
                'client' => $client ? $client->nom : 'Non assignée',
                'sim' => $displaySim,
                'operateur' => $sim->operateur ?? 'N/A',
                'statut' => $sim->statut,
                'url' => route('sims.show', $sim->id_sim),
            ];
        }

        return view('search.results', [
            'results' => $results,
            'query' => $query,
        ]);
    }
}
