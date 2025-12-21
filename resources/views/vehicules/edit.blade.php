@extends('layouts.app')

@section('title', 'Modifier véhicule - Vtrack')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-pencil"></i> Modifier véhicule
            </div>
            <div class="card-body">
                <form action="{{ route('vehicules.update', $vehicule->id_vehicule) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="immatriculation" class="form-label">Immatriculation *</label>
                            <input type="text" class="form-control @error('immatriculation') is-invalid @enderror" 
                                   name="immatriculation" id="immatriculation" value="{{ old('immatriculation', $vehicule->immatriculation) }}" required>
                            @error('immatriculation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="client_id" class="form-label">Client</label>
                            <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror">
                                <option value="">Sélectionner un client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id_client }}" {{ old('client_id', $vehicule->client_id) == $client->id_client ? 'selected' : '' }}>
                                        {{ $client->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sim_id" class="form-label">SIM</label>
                            <select name="sim_id" id="sim_id" class="form-select">
                                <option value="">Aucune SIM</option>
                                @foreach($sims as $sim)
                                    <option value="{{ $sim->id_sim }}" {{ old('sim_id', $vehicule->sim_id) == $sim->id_sim ? 'selected' : '' }}>
                                        {{ $sim->last5 }}{{ $sim->numero ? ' - ' . $sim->numero : '' }}{{ $sim->operateur ? ' (' . $sim->operateur . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="statut" class="form-label">Statut *</label>
                            <select name="statut" id="statut" class="form-select" required>
                                <option value="actif" {{ old('statut', $vehicule->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="suspendu" {{ old('statut', $vehicule->statut) == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="raison_suspension_group" style="display: {{ old('statut', $vehicule->statut) == 'suspendu' ? 'block' : 'none' }};">
                            <label for="raison_suspension" class="form-label">Raison de suspension *</label>
                            <textarea name="raison_suspension" id="raison_suspension" class="form-control" rows="3">{{ old('raison_suspension', $vehicule->raison_suspension) }}</textarea>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('vehicules.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('statut').addEventListener('change', function() {
        const raisonGroup = document.getElementById('raison_suspension_group');
        if (this.value === 'suspendu') {
            raisonGroup.style.display = 'block';
            document.getElementById('raison_suspension').required = true;
        } else {
            raisonGroup.style.display = 'none';
            document.getElementById('raison_suspension').required = false;
        }
    });
</script>
@endsection
