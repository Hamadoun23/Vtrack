@extends('layouts.app')

@section('title', 'Clients - Vtrack')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-people"></i> Liste des clients</h2>
            <a href="{{ route('clients.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nouveau client
            </a>
        </div>
    </div>
</div>

@if($clients->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Contact</th>
                            <th>Véhicules</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td><strong>{{ $client->nom }}</strong></td>
                            <td>{{ $client->contact ?? 'Non renseigné' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $client->vehicules->count() }}</span>
                            </td>
                            <td>
                                <a href="{{ route('clients.show', $client->id_client) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('clients.edit', $client->id_client) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('clients.destroy', $client->id_client) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr ?')">
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
            <p class="text-muted mt-3">Aucun client enregistré.</p>
            <a href="{{ route('clients.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Créer le premier client
            </a>
        </div>
    </div>
@endif
@endsection
