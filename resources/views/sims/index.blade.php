@extends('layouts.app')

@section('title', 'SIM - Vtrack')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-sim"></i> Liste des SIM</h2>
            <a href="{{ route('sims.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nouvelle SIM
            </a>
        </div>
    </div>
</div>

@if($sims->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ICCID</th>
                            <th>Last4</th>
                            <th>Numéro</th>
                            <th>Opérateur</th>
                            <th>Véhicule</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sims as $sim)
                        <tr>
                            <td><strong>{{ $sim->iccid }}</strong></td>
                            <td><span class="badge bg-secondary">{{ $sim->last5 }}</span></td>
                            <td>{{ $sim->numero ?? 'N/A' }}</td>
                            <td>{{ $sim->operateur ?? 'N/A' }}</td>
                            <td>
                                @if($sim->vehicule)
                                    <a href="{{ route('vehicules.show', $sim->vehicule->id_vehicule) }}">
                                        {{ $sim->vehicule->immatriculation }}
                                    </a>
                                @else
                                    <span class="text-muted">Non assignée</span>
                                @endif
                            </td>
                            <td>
                                @if($sim->statut == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($sim->statut == 'bloquee')
                                    <span class="badge bg-danger">Bloquée</span>
                                @else
                                    <span class="badge bg-warning">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('sims.show', $sim->id_sim) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('sims.edit', $sim->id_sim) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('sims.destroy', $sim->id_sim) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette SIM ?')">
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
            <p class="text-muted mt-3">Aucune SIM enregistrée.</p>
            <a href="{{ route('sims.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Créer la première SIM
            </a>
        </div>
    </div>
@endif
@endsection
