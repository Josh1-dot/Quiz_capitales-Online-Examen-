 <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ////////////////////////// --}}

    <style>
        /* Animation du chronomètre */
        #countdown {
            font-family: 'Arial', sans-serif;
            animation: pulse 1s infinite alternate;
        }
        @keyframes pulse {
            from { transform: scale(1); }
            to { transform: scale(1.05); }
        }
        /* Style des inputs désactivés */
        .cursor-not-allowed {
            cursor: not-allowed;
        }
    </style>


    <title>{{ config('app.name', 'Quiz Capitales') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet" />
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .navbar {
            padding: 0.8rem 0;
        }
        .nav-cta {
            transition: all 0.3s ease;
        }
        .nav-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                    <i class="bi bi-globe-europe-africa me-2"></i>
                    {{ config('app.name', 'Quiz Capitales') }}
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <!-- Left Side -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="bi bi-house-door me-1"></i> Accueil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('game.history') }}">
                                <i class="bi bi-clock-history me-1"></i> History
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('game.leaderboard') }}">
                                <i class="bi bi-trophy me-1"></i> Dashboard
                            </a>
                        </li>
                    </ul>

                    <!-- Right Side -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item me-2">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Connexion
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-outline-primary" href="{{ route('register') }}">
                                        <i class="bi bi-person-plus me-1"></i> Inscription
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item me-3">
                                <a href="{{ route('game.play') }}" class="btn btn-success nav-cta px-3">
                                    <i class="bi bi-controller me-1"></i> Jouer
                                </a>
                            </li>
                            
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle me-1"></i>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">

                                        <i class="bi bi-person me-2"></i> Profil
                                    </a>
                                    <a class="dropdown-item" href="{{ route('game.history') }}">
                                        <i class="bi bi-graph-up me-2"></i> Statistiques
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-light py-4 mt-5">
            <div class="container text-center">
                <p class="mb-0 text-muted">
                    <small>
                        © {{ date('Y') }} Quiz Capitales - 
                        <a href="#" class="text-decoration-none">Mentions légales</a> - 
                        <a href="#" class="text-decoration-none">Contact</a>
                    </small>
                </p>
            </div>
        </footer>
    </div>
</body>
</html>