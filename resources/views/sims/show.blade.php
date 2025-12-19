@extends('layouts.app')

@section('title', 'Détails SIM - Vtrack')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-sim"></i> SIM {{ $sim->last5 }}</h2>
            <div>
                <a href="{{ route('sims.edit', $sim->id_sim) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
                <a href="{{ route('sims.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle"></i> Informations
            </div>
            <div class="card-body">
                <p><strong>ICCID:</strong><br>{{ $sim->iccid }}</p>
                <p><strong>Last5:</strong><br><span class="badge bg-secondary">{{ $sim->last5 }}</span></p>
                <p><strong>Numéro:</strong><br>{{ $sim->numero ?? 'Non renseigné' }}</p>
                <p><strong>Opérateur:</strong><br>{{ $sim->operateur ?? 'Non renseigné' }}</p>
                <p><strong>Statut:</strong><br>
                    @if($sim->statut == 'active')
                        <span class="badge bg-success">Active</span>
                    @elseif($sim->statut == 'bloquee')
                        <span class="badge bg-danger">Bloquée</span>
                        @if($sim->raison_blocage)
                            <br><small class="text-muted"><strong>Raison:</strong> {{ $sim->raison_blocage }}</small>
                        @endif
                    @else
                        <span class="badge bg-warning">Inactive</span>
                    @endif
                </p>
                @if($sim->vehicule)
                    <p><strong>Véhicule:</strong><br>
                        <a href="{{ route('vehicules.show', $sim->vehicule->id_vehicule) }}">
                            {{ $sim->vehicule->immatriculation }}
                        </a>
                    </p>
                    <p><strong>Client:</strong><br>
                        @if($sim->vehicule->client)
                            <a href="{{ route('clients.show', $sim->vehicule->client->id_client) }}">
                                {{ $sim->vehicule->client->nom }}
                            </a>
                        @else
                            <span class="text-muted">Aucun client</span>
                        @endif
                    </p>
                @else
                    <p><strong>Véhicule:</strong><br><span class="text-muted">Non assignée</span></p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-gear"></i> Actions
            </div>
            <div class="card-body">
                @if($sim->statut == 'active')
                    <form action="{{ route('sims.bloquer', $sim->id_sim) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="raison_blocage" class="form-label">Raison de blocage *</label>
                            <textarea name="raison_blocage" id="raison_blocage" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-shield-x"></i> Bloquer la SIM
                        </button>
                    </form>
                @elseif($sim->statut == 'bloquee')
                    <form action="{{ route('sims.debloquer', $sim->id_sim) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Débloquer la SIM
                        </button>
                    </form>
                @endif

                @if(!$sim->vehicule)
                    <form action="{{ route('sims.destroy', $sim->id_sim) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette SIM ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> Cette SIM ne peut pas être supprimée car elle est associée à un véhicule.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
