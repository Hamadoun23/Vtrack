@extends('layouts.app')

@section('title', 'Dashboard - Vtrack')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-4"><i class="bi bi-speedometer2"></i> Dashboard</h1>
    </div>
</div>

<!-- Statistiques principales -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $totalClients }}</div>
                    <div>Clients</div>
                </div>
                <i class="bi bi-people-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $totalVehicules }}</div>
                    <div>Véhicules</div>
                </div>
                <i class="bi bi-car-front-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $totalSims }}</div>
                    <div>SIM</div>
                </div>
                <i class="bi bi-sim-fill stat-icon"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card {{ $vehiculesSuspendus > 0 ? 'danger' : 'success' }}">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-number">{{ $vehiculesSuspendus }}</div>
                    <div>Suspendus</div>
                </div>
                <i class="bi bi-exclamation-triangle-fill stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- Raccourcis rapides -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-lightning-charge"></i> Actions rapides
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('vehicules.create') }}" class="quick-action-btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-car-front"></i>
                            <div>Ajouter un véhicule</div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('sims.create') }}" class="quick-action-btn" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="bi bi-sim"></i>
                            <div>Ajouter une SIM</div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('clients.create') }}" class="quick-action-btn" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="bi bi-person-plus"></i>
                            <div>Ajouter un client</div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('search') }}" class="quick-action-btn" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                            <i class="bi bi-search"></i>
                            <div>Recherche rapide</div>
                        </a>
                    </div>
                    <div class="col-md-6 mt-3">
                        <a href="{{ route('dashboard.exportCsv') }}" class="quick-action-btn" style="background: linear-gradient(135deg, #0f9d58 0%, #0b8043 100%);">
                            <i class="bi bi-file-earmark-excel"></i>
                            <div>Exporter la base (Excel/CSV)</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bar-chart"></i> Statistiques détaillées
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-check-circle text-success"></i> Véhicules actifs:</span>
                            <strong>{{ $vehiculesActifs }}</strong>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-x-circle text-danger"></i> Véhicules suspendus:</span>
                            <strong>{{ $vehiculesSuspendus }}</strong>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-check-circle text-success"></i> SIM actives:</span>
                            <strong>{{ $simsActives }}</strong>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-shield-x text-danger"></i> SIM bloquées:</span>
                            <strong>{{ $simsBloquees }}</strong>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-car-front text-warning"></i> Véhicules sans SIM:</span>
                            <strong>{{ $vehiculesSansSim }}</strong>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-sim text-info"></i> SIM non assignées:</span>
                            <strong>{{ $simsNonAssignees }}</strong>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-tools text-primary"></i> Total interventions:</span>
                            <strong>{{ $totalInterventions }}</strong>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="d-flex justify-content-between">
                            <span><i class="bi bi-calendar-check text-success"></i> Interventions ce mois:</span>
                            <strong>{{ $interventionsCeMois }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notifications et alertes -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bell"></i> Notifications et alertes
            </div>
            <div class="card-body">
                @if($vehiculesSuspendus > 0)
                    <div class="notification-item danger">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><i class="bi bi-exclamation-triangle"></i> {{ $vehiculesSuspendus }} véhicule(s) suspendu(s)</strong>
                                <p class="mb-0 text-muted">Action requise: Vérifier les raisons de suspension</p>
                            </div>
                            <a href="{{ route('vehicules.index') }}?statut=suspendu" class="btn btn-sm btn-outline-danger">Voir</a>
                        </div>
                    </div>
                @endif

                @if($simsBloquees > 0)
                    <div class="notification-item warning">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><i class="bi bi-shield-x"></i> {{ $simsBloquees }} SIM bloquée(s)</strong>
                                <p class="mb-0 text-muted">Action requise: Vérifier les raisons de blocage</p>
                            </div>
                            <a href="{{ route('sims.index') }}?statut=bloquee" class="btn btn-sm btn-outline-warning">Voir</a>
                        </div>
                    </div>
                @endif

                @if($vehiculesSansSim > 0)
                    <div class="notification-item info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><i class="bi bi-info-circle"></i> {{ $vehiculesSansSim }} véhicule(s) sans SIM</strong>
                                <p class="mb-0 text-muted">Action suggérée: Assigner une SIM à ces véhicules</p>
                            </div>
                            <a href="{{ route('vehicules.index') }}" class="btn btn-sm btn-outline-info">Voir</a>
                        </div>
                    </div>
                @endif

                @if($simsNonAssignees > 0)
                    <div class="notification-item success">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><i class="bi bi-check-circle"></i> {{ $simsNonAssignees }} SIM disponible(s)</strong>
                                <p class="mb-0 text-muted">SIM actives prêtes à être assignées</p>
                            </div>
                            <a href="{{ route('sims.index') }}?statut=active&non_assignee=1" class="btn btn-sm btn-outline-success">Voir</a>
                        </div>
                    </div>
                @endif

                @if($vehiculesSuspendus == 0 && $simsBloquees == 0 && $vehiculesSansSim == 0)
                    <div class="notification-item success">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong>Tout est en ordre !</strong>
                                <p class="mb-0 text-muted">Aucune action requise pour le moment.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Derniers véhicules et alertes -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i> Derniers véhicules ajoutés
            </div>
            <div class="card-body">
                @if($derniersVehicules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Immatriculation</th>
                                    <th>Client</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($derniersVehicules as $vehicule)
                                <tr>
                                    <td>
                                        <a href="{{ route('vehicules.show', $vehicule->id_vehicule) }}">
                                            {{ $vehicule->immatriculation ?? 'Non renseignée' }}
                                        </a>
                                    </td>
                                    <td>{{ $vehicule->client->nom ?? 'Aucun client' }}</td>
                                    <td>
                                        @if($vehicule->statut == 'actif')
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Suspendu</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Aucun véhicule enregistré</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <i class="bi bi-exclamation-triangle"></i> Véhicules suspendus récents
            </div>
            <div class="card-body">
                @if($vehiculesSuspendusRecents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Immatriculation</th>
                                    <th>Client</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehiculesSuspendusRecents as $vehicule)
                                <tr>
                                    <td>
                                        <a href="{{ route('vehicules.show', $vehicule->id_vehicule) }}">
                                            {{ $vehicule->immatriculation ?? 'Non renseignée' }}
                                        </a>
                                    </td>
                                    <td>{{ $vehicule->client->nom ?? 'Aucun client' }}</td>
                                    <td>
                                        <a href="{{ route('vehicules.show', $vehicule->id_vehicule) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Aucun véhicule suspendu</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- SIM bloquées récentes -->
@if($simsBloqueesRecentes->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <i class="bi bi-shield-x"></i> SIM bloquées récentes
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ICCID</th>
                                <th>Last4</th>
                                <th>Véhicule</th>
                                <th>Client</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($simsBloqueesRecentes as $sim)
                            <tr>
                                <td>{{ $sim->iccid }}</td>
                                <td>{{ $sim->last5 }}</td>
                                <td>
                                    @if($sim->vehicule)
                                        <a href="{{ route('vehicules.show', $sim->vehicule->id_vehicule) }}">
                                            {{ $sim->vehicule->immatriculation ?? 'Non renseignée' }}
                                        </a>
                                    @else
                                        <span class="text-muted">Non assignée</span>
                                    @endif
                                </td>
                                <td>
                                    @if($sim->vehicule && $sim->vehicule->client)
                                        {{ $sim->vehicule->client->nom }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('sims.show', $sim->id_sim) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> Voir
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Dernières interventions -->
@if($dernieresInterventions->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-tools"></i> Dernières interventions
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Véhicule</th>
                                <th>Client</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dernieresInterventions as $intervention)
                            <tr>
                                <td>
                                    <strong>{{ $intervention->date_intervention->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $intervention->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    @if($intervention->vehicule)
                                        <a href="{{ route('vehicules.show', $intervention->vehicule->id_vehicule) }}">
                                            {{ $intervention->vehicule->immatriculation ?? 'Non renseignée' }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($intervention->vehicule && $intervention->vehicule->client)
                                        <a href="{{ route('clients.show', $intervention->vehicule->client->id_client) }}">
                                            {{ $intervention->vehicule->client->nom }}
                                        </a>
                                    @else
                                        <span class="text-muted">Aucun client</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 300px;" title="{{ $intervention->description }}">
                                        {{ Str::limit($intervention->description, 50) }}
                                    </span>
                                </td>
                                <td>
                                    @if($intervention->vehicule)
                                        <a href="{{ route('vehicules.show', $intervention->vehicule->id_vehicule) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endsection

