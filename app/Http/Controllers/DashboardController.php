<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Vehicule;
use App\Models\Sim;
use App\Models\Intervention;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $totalClients = Client::count();
        $totalVehicules = Vehicule::count();
        $totalSims = Sim::count();
        
        // Véhicules actifs/suspendus
        $vehiculesActifs = Vehicule::where('statut', 'actif')->count();
        $vehiculesSuspendus = Vehicule::where('statut', 'suspendu')->count();
        
        // SIM actives/bloquées/inactives
        $simsActives = Sim::where('statut', 'active')->count();
        $simsBloquees = Sim::where('statut', 'bloquee')->count();
        $simsInactives = Sim::where('statut', 'inactive')->count();
        
        // Véhicules sans SIM
        $vehiculesSansSim = Vehicule::whereNull('sim_id')->count();
        
        // SIM non assignées
        $simsNonAssignees = Sim::whereDoesntHave('vehicule')->where('statut', 'active')->count();
        
        // Derniers véhicules ajoutés
        $derniersVehicules = Vehicule::with(['client', 'sim'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Véhicules suspendus récents
        $vehiculesSuspendusRecents = Vehicule::where('statut', 'suspendu')
            ->with('client')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // SIM bloquées récentes
        $simsBloqueesRecentes = Sim::where('statut', 'bloquee')
            ->with('vehicule.client')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        // Statistiques interventions
        $totalInterventions = Intervention::count();
        $interventionsCeMois = Intervention::whereMonth('date_intervention', now()->month)
            ->whereYear('date_intervention', now()->year)
            ->count();
        
        // Dernières interventions
        $dernieresInterventions = Intervention::with('vehicule.client')
            ->orderBy('date_intervention', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalClients',
            'totalVehicules',
            'totalSims',
            'vehiculesActifs',
            'vehiculesSuspendus',
            'simsActives',
            'simsBloquees',
            'simsInactives',
            'vehiculesSansSim',
            'simsNonAssignees',
            'derniersVehicules',
            'vehiculesSuspendusRecents',
            'simsBloqueesRecentes',
            'totalInterventions',
            'interventionsCeMois',
            'dernieresInterventions'
        ));
    }

    /**
     * Export de la base de données principale au format CSV (ouvrable dans Excel).
     */
    public function exportCsv()
    {
        $fileName = 'vtrack_export_' . now()->format('Ymd_His') . '.csv';

        $callback = function () {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 pour un bon affichage dans Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            $writeSection = function ($title, array $columns, $rows) use ($handle) {
                // Titre de section
                fputcsv($handle, [$title]);
                // En-têtes
                fputcsv($handle, $columns);

                foreach ($rows as $row) {
                    $line = [];
                    foreach ($columns as $col) {
                        $value = $row[$col] ?? '';
                        if ($value instanceof \DateTimeInterface) {
                            $value = $value->format('Y-m-d H:i:s');
                        }
                        $line[] = $value;
                    }
                    fputcsv($handle, $line);
                }

                // Ligne vide entre les sections
                fputcsv($handle, []);
                fputcsv($handle, []);
            };

            // USERS
            $users = User::select('id_user', 'nom', 'prenom', 'email', 'created_at', 'updated_at')->get()->toArray();
            $writeSection('USERS', ['id_user', 'nom', 'prenom', 'email', 'created_at', 'updated_at'], $users);

            // CLIENTS
            $clients = Client::select('id_client', 'nom', 'contact', 'note', 'created_at', 'updated_at')->get()->toArray();
            $writeSection('CLIENTS', ['id_client', 'nom', 'contact', 'note', 'created_at', 'updated_at'], $clients);

            // SIMS
            $sims = Sim::select('id_sim', 'iccid', 'last5', 'numero', 'operateur', 'statut', 'raison_blocage', 'created_at', 'updated_at')->get()->toArray();
            $writeSection('SIMS', ['id_sim', 'iccid', 'last5', 'numero', 'operateur', 'statut', 'raison_blocage', 'created_at', 'updated_at'], $sims);

            // VEHICULES
            $vehicules = Vehicule::select('id_vehicule', 'immatriculation', 'marque_modele', 'client_id', 'sim_id', 'statut', 'raison_suspension', 'created_at', 'updated_at')->get()->toArray();
            $writeSection('VEHICULES', ['id_vehicule', 'immatriculation', 'marque_modele', 'client_id', 'sim_id', 'statut', 'raison_suspension', 'created_at', 'updated_at'], $vehicules);

            // INTERVENTIONS
            $interventions = Intervention::select('id_intervention', 'vehicule_id', 'description', 'date_intervention', 'created_at', 'updated_at')->get()->toArray();
            $writeSection('INTERVENTIONS', ['id_intervention', 'vehicule_id', 'description', 'date_intervention', 'created_at', 'updated_at'], $interventions);

            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
