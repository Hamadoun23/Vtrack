<x-guest-layout>
    <x-slot name="title">Inscription - Vtrack</x-slot>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="nom" class="form-label">
                <i class="bi bi-person"></i> Nom
            </label>
            <input type="text" 
                   class="form-control @error('nom') is-invalid @enderror" 
                   id="nom" 
                   name="nom" 
                   value="{{ old('nom') }}" 
                   required 
                   autofocus>
            @error('nom')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="prenom" class="form-label">
                <i class="bi bi-person"></i> Prénom
            </label>
            <input type="text" 
                   class="form-control @error('prenom') is-invalid @enderror" 
                   id="prenom" 
                   name="prenom" 
                   value="{{ old('prenom') }}" 
                   required>
            @error('prenom')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="bi bi-envelope"></i> Email
            </label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="bi bi-lock"></i> Mot de passe
            </label>
            <input type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   id="password" 
                            name="password"
                   required 
                   autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">
                <i class="bi bi-lock-fill"></i> Confirmer le mot de passe
            </label>
            <input type="password" 
                   class="form-control" 
                   id="password_confirmation" 
                   name="password_confirmation" 
                   required 
                   autocomplete="new-password">
        </div>

        <div class="d-grid gap-2 mb-3">
            <button type="submit" class="btn btn-primary" style="background-color: #2538A1; border-color: #2538A1;">
                <i class="bi bi-person-plus"></i> S'inscrire
            </button>
        </div>

        <hr class="my-4">

        <div class="text-center">
            <p class="mb-0">Déjà un compte ? 
                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                    Se connecter
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
