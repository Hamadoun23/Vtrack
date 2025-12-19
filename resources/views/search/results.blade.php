@extends('layouts.app')

@section('title', 'Recherche - Vtrack')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-search"></i> Recherche rapide
            </div>
            <div class="card-body">
                <form action="{{ route('search') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control" value="{{ $query ?? '' }}" 
                               placeholder="Rechercher par nom client, téléphone, immatriculation, SIM, opérateur..." autofocus>
                        <button class="btn btn-primary" type="submit">Rechercher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(isset($results) && count($results) > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-list-ul"></i> Résultats de recherche pour "{{ $query }}"
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Titre</th>
                                    <th>Détails</th>
                                    <th>Client</th>
                                    <th>SIM</th>
                                    <th>Opérateur</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                <tr>
                                    <td>
                                        @if($result['type'] == 'client')
                                            <span class="badge bg-info">
                                                <i class="bi bi-people"></i> Client
                                            </span>
                                        @elseif($result['type'] == 'véhicule')
                                            <span class="badge bg-success">
                                                <i class="bi bi-car-front"></i> Véhicule
                                            </span>
                                        @elseif($result['type'] == 'sim')
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-sim"></i> SIM
                                            </span>
                                        @else
                                            <span class="badge bg-primary">{{ ucfirst($result['type']) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $result['titre'] }}</strong>
                                        @if($result['sous_titre'])
                                            <br><small class="text-muted">{{ $result['sous_titre'] }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($result['info_supplementaire']))
                                            <small>{{ $result['info_supplementaire'] }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $result['client'] }}</td>
                                    <td>{{ $result['sim'] }}</td>
                                    <td>{{ $result['operateur'] }}</td>
                                    <td>
                                        @if($result['statut'] == 'actif' || $result['statut'] == 'active')
                                            <span class="badge bg-success">{{ ucfirst($result['statut']) }}</span>
                                        @elseif($result['statut'] == 'suspendu' || $result['statut'] == 'bloquee')
                                            <span class="badge bg-danger">{{ ucfirst($result['statut']) }}</span>
                                        @elseif($result['statut'] == 'inactive')
                                            <span class="badge bg-secondary">{{ ucfirst($result['statut']) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ $result['url'] }}" class="btn btn-sm btn-primary">
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
@elseif(isset($query))
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Aucun résultat trouvé pour "{{ $query }}"
            </div>
        </div>
    </div>
@endif
@endsection
