@extends('layouts.app')

@section('title', 'Nouvelle SIM - Vtrack')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-sim"></i> Nouvelle SIM
            </div>
            <div class="card-body">
                <form action="{{ route('sims.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="iccid" class="form-label">ICCID</label>
                            <input type="text" class="form-control @error('iccid') is-invalid @enderror" 
                                   name="iccid" id="iccid" value="{{ old('iccid') }}">
                            @error('iccid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last5" class="form-label">Last5 *</label>
                            <input type="text" class="form-control @error('last5') is-invalid @enderror" 
                                   name="last5" id="last5" value="{{ old('last5') }}" maxlength="5" required>
                            @error('last5')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero" class="form-label">Numéro</label>
                            <input type="text" class="form-control @error('numero') is-invalid @enderror" 
                                   name="numero" id="numero" value="{{ old('numero') }}">
                            @error('numero')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="operateur" class="form-label">Opérateur</label>
                            <input type="text" class="form-control @error('operateur') is-invalid @enderror" 
                                   name="operateur" id="operateur" value="{{ old('operateur') }}">
                            @error('operateur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="statut" class="form-label">Statut *</label>
                            <select name="statut" id="statut" class="form-select" required>
                                <option value="active" {{ old('statut') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('statut') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="bloquee" {{ old('statut') == 'bloquee' ? 'selected' : '' }}>Bloquée</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="raison_blocage_group" style="display: none;">
                            <label for="raison_blocage" class="form-label">Raison de blocage *</label>
                            <textarea name="raison_blocage" id="raison_blocage" class="form-control" rows="3">{{ old('raison_blocage') }}</textarea>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('sims.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Créer
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
        const raisonGroup = document.getElementById('raison_blocage_group');
        if (this.value === 'bloquee') {
            raisonGroup.style.display = 'block';
            document.getElementById('raison_blocage').required = true;
        } else {
            raisonGroup.style.display = 'none';
            document.getElementById('raison_blocage').required = false;
        }
    });
    if (document.getElementById('statut').value === 'bloquee') {
        document.getElementById('raison_blocage_group').style.display = 'block';
    }
</script>
@endsection
