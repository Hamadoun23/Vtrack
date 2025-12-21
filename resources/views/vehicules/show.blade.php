@extends('layouts.app')

@section('title', 'Détails véhicule - Vtrack')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-car-front"></i> {{ $vehicule->immatriculation ?? 'Véhicule sans immatriculation' }}</h2>
            <div>
                <a href="{{ route('vehicules.edit', $vehicule->id_vehicule) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
                <a href="{{ route('vehicules.index') }}" class="btn btn-secondary">
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
                <p><strong>Client:</strong><br>
                    @if($vehicule->client)
                        <a href="{{ route('clients.show', $vehicule->client->id_client) }}">
                            {{ $vehicule->client->nom }}
                        </a>
                    @else
                        <span class="text-muted">Aucun client</span>
                    @endif
                </p>
                <p><strong>Statut:</strong><br>
                    @if($vehicule->statut == 'actif')
                        <span class="badge bg-success">Actif</span>
                    @else
                        <span class="badge bg-danger">Suspendu</span>
                        @if($vehicule->raison_suspension)
                            <br><small class="text-muted"><strong>Raison:</strong> {{ $vehicule->raison_suspension }}</small>
                        @endif
                    @endif
                </p>
                @if($vehicule->sim)
                    <p><strong>SIM:</strong><br>
                        <a href="{{ route('sims.show', $vehicule->sim->id_sim) }}">
                            {{ $vehicule->sim->numero ?? 'N/A' }}
                        </a>
                        @if($vehicule->sim->operateur)
                            ({{ $vehicule->sim->operateur }})
                        @endif
                    </p>
                @else
                    <p><strong>SIM:</strong><br><span class="text-muted">Aucune SIM assignée</span></p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-tools"></i> Actions
            </div>
            <div class="card-body">
                @if($vehicule->statut == 'actif')
                    <form action="{{ route('vehicules.suspendre', $vehicule->id_vehicule) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="mb-3">
                            <label for="raison_suspension" class="form-label">Raison de suspension *</label>
                            <textarea name="raison_suspension" id="raison_suspension" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-pause-circle"></i> Suspendre le véhicule
                        </button>
                    </form>
                @else
                    <form action="{{ route('vehicules.reactiver', $vehicule->id_vehicule) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-play-circle"></i> Réactiver le véhicule
                        </button>
                    </form>
                @endif

                <hr>

                <h6 class="mb-3">Remplacer la SIM</h6>
                <form action="{{ route('vehicules.remplacerSim', $vehicule->id_vehicule) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nouvelle_sim_id" class="form-label">Nouvelle SIM *</label>
                        <select name="nouvelle_sim_id" id="nouvelle_sim_id" class="form-select" required>
                            <option value="">Sélectionner une SIM</option>
                            @foreach($simsDisponibles as $sim)
                                <option value="{{ $sim->id_sim }}">{{ $sim->last5 }}{{ $sim->numero ? ' - ' . $sim->numero : '' }}{{ $sim->operateur ? ' (' . $sim->operateur . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-arrow-repeat"></i> Remplacer la SIM
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Section Interventions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-tools"></i> Interventions ({{ $vehicule->interventions->count() }})
            </div>
            <div class="card-body">
                <!-- Formulaire pour ajouter une intervention -->
                <div class="mb-4">
                    <h5 class="mb-3">Ajouter une intervention</h5>
                    <form action="{{ route('interventions.store', $vehicule->id_vehicule) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="date_intervention" class="form-label">Date de l'intervention *</label>
                                <input type="date" class="form-control @error('date_intervention') is-invalid @enderror" 
                                       name="date_intervention" id="date_intervention" value="{{ old('date_intervention', date('Y-m-d')) }}" required>
                                @error('date_intervention')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          name="description" id="description" rows="3" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle"></i> Ajouter l'intervention
                        </button>
                    </form>
                </div>

                <hr>

                <!-- Liste des interventions -->
                @if($vehicule->interventions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehicule->interventions->sortByDesc('date_intervention') as $intervention)
                                <tr>
                                    <td>
                                        <strong>{{ $intervention->date_intervention->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $intervention->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>{{ $intervention->description }}</td>
                                    <td>
                                        <form action="{{ route('interventions.destroy', [$vehicule->id_vehicule, $intervention->id_intervention]) }}" 
                                              method="POST" 
                                              onsubmit="return confirmDelete(event, 'Êtes-vous sûr de vouloir supprimer cette intervention ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center">Aucune intervention enregistrée pour ce véhicule.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
