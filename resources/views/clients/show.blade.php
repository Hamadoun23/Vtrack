@extends('layouts.app')

@section('title', 'Détails client - Vtrack')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-person"></i> {{ $client->nom }}</h2>
            <div>
                <a href="{{ route('clients.edit', $client->id_client) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Informations
            </div>
            <div class="card-body">
                <p><strong>Contact:</strong><br>{{ $client->contact }}</p>
                @if($client->note)
                    <p><strong>Note:</strong><br>{{ $client->note }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-car-front"></i> Véhicules ({{ $client->vehicules->count() }})
            </div>
            <div class="card-body">
                @if($client->vehicules->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Immatriculation</th>
                                    <th>Marque/Modèle</th>
                                    <th>SIM</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($client->vehicules as $vehicule)
                                <tr>
                                    <td><strong>{{ $vehicule->immatriculation ?? 'Non renseignée' }}</strong></td>
                                    <td>{{ $vehicule->marque_modele ?? 'Non renseigné' }}</td>
                                    <td>{{ $vehicule->sim ? ($vehicule->sim->numero ?? 'N/A') : 'Aucune' }}</td>
                                    <td>
                                        @if($vehicule->statut == 'actif')
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Suspendu</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('vehicules.show', $vehicule->id_vehicule) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Aucun véhicule pour ce client.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
