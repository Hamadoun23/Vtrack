<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Vtrack')</title>

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#2538A1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Vtrack">
    <link rel="apple-touch-icon" href="{{ asset('logovalerie.jpeg') }}">

    <!-- Manifest PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('logovalerie.jpeg') }}">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2538A1;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background-color: var(--primary-color) !important;
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white !important;
        }

        .navbar-custom .nav-link:hover {
            color: #e0e0e0 !important;
        }

        .stat-card {
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            color: white;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .stat-card.info {
            background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
        }

        .stat-card.success {
            background: linear-gradient(135deg, #198754 0%, #146c43 100%);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
        }

        .stat-card.danger {
            background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: white;
        }

        .stat-card > div > div:first-child {
            color: white;
        }

        .stat-icon {
            font-size: 2.5rem;
            color: white;
            opacity: 0.8;
        }

        .quick-action-btn {
            display: block;
            padding: 20px;
            border-radius: 10px;
            color: white;
            text-decoration: none;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            color: white;
            text-decoration: none;
        }

        .quick-action-btn i {
            font-size: 2rem;
            display: block;
            margin-bottom: 10px;
        }

        .quick-action-btn div {
            font-weight: 500;
            font-size: 0.95rem;
        }

        .notification-item {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            border-left: 4px solid;
        }

        .notification-item.danger {
            background-color: #f8d7da;
            border-left-color: #dc3545;
        }

        .notification-item.warning {
            background-color: #fff3cd;
            border-left-color: #ffc107;
        }

        .notification-item.info {
            background-color: #d1ecf1;
            border-left-color: #0dcaf0;
        }

        .notification-item.success {
            background-color: #d4edda;
            border-left-color: #198754;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            padding: 15px 20px;
        }

        .card-header i {
            margin-right: 8px;
        }

        .alert-auto-hide {
            animation: fadeOut 0.5s ease-in 4.5s forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                display: none;
            }
        }
    </style>

    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('logovalerie.jpeg') }}" alt="Vtrack" height="40" class="me-2">
                <strong>Vtrack</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('vehicules.*') ? 'active' : '' }}" href="{{ route('vehicules.index') }}">
                            <i class="bi bi-car-front"></i> Véhicules
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('sims.*') ? 'active' : '' }}" href="{{ route('sims.index') }}">
                            <i class="bi bi-sim"></i> SIM
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}" href="{{ route('clients.index') }}">
                            <i class="bi bi-people"></i> Clients
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('search') ? 'active' : '' }}" href="{{ route('search') }}" title="Recherche">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person"></i> Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Connexion</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show alert-auto-hide" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show alert-auto-hide" role="alert">
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <strong>Erreurs :</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Modal de confirmation personnalisée -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmDeleteMessage">
                    Êtes-vous sûr de vouloir supprimer cette intervention ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" id="confirmDeleteBtn">Oui</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-hide alerts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-auto-hide');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });

        // Fonction de confirmation personnalisée
        function confirmDelete(event, message) {
            event.preventDefault();
            const form = event.target.closest('form');
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            const messageEl = document.getElementById('confirmDeleteMessage');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            
            if (message) {
                messageEl.textContent = message;
            }
            
            confirmBtn.onclick = function() {
                modal.hide();
                form.submit();
            };
            
            modal.show();
            return false;
        }
    </script>

    <!-- Service Worker Registration -->
    <script src="{{ asset('register-sw.js') }}"></script>
    
    <!-- PWA Installation Button (Version améliorée) -->
    <script src="{{ asset('pwa-install-fixed.js') }}"></script>
    
    <!-- PWA Debug (pour diagnostic) -->
    <script src="{{ asset('pwa-debug.js') }}"></script>

    @yield('scripts')
</body>
</html>
