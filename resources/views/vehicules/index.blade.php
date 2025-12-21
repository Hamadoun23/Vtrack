@extends('layouts.app')

@section('title', 'Véhicules - Vtrack')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-car-front"></i> Liste des véhicules</h2>
            <a href="{{ route('vehicules.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nouveau véhicule
            </a>
        </div>
    </div>
</div>

@if($vehicules->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Immatriculation</th>
                            <th>Client</th>
                            <th>SIM</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehicules as $vehicule)
                        <tr>
                            <td><strong>{{ $vehicule->immatriculation ?? 'Non renseignée' }}</strong></td>
                            <td>
                                @if($vehicule->client)
                                    <a href="{{ route('clients.show', $vehicule->client->id_client) }}">
                                        {{ $vehicule->client->nom }}
                                    </a>
                                @else
                                    <span class="text-muted">Aucun client</span>
                                @endif
                            </td>
                            <td>
                                @if($vehicule->sim)
                                    <a href="{{ route('sims.show', $vehicule->sim->id_sim) }}">
                                        {{ $vehicule->sim->last5 ?? 'N/A' }}
                                    </a>
                                @else
                                    <span class="text-muted">Aucune</span>
                                @endif
                            </td>
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
                                <a href="{{ route('vehicules.edit', $vehicule->id_vehicule) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('vehicules.destroy', $vehicule->id_vehicule) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
            <p class="text-muted mt-3">Aucun véhicule enregistré.</p>
            <a href="{{ route('vehicules.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Créer le premier véhicule
            </a>
        </div>
    </div>
@endif
@endsection
