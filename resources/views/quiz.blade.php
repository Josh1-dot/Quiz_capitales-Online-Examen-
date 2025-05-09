@extends('layouts.app')

@section('content')
<div class="container py-4">
    @if(session('cooldown'))
        <div id="cooldown-alert" class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex flex-column flex-md-row align-items-center">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <i class="fas fa-clock fa-2x me-3"></i>
                    <div>
                        <h5 class="mb-1">{{ session('cooldown.message') }}</h5>
                        <p class="mb-1">Prochain quiz disponible à <strong>{{ session('cooldown.available_at') }}</strong></p>
                    </div>
                </div>
                <div class="ms-md-auto ps-md-3">
                    <p class="mb-0">Temps restant : 
                        <span id="countdown-timer" class="badge bg-dark fs-6">{{ session('cooldown.remaining') }}</span>
                    </p>
                </div>
            </div>
            <div class="progress mt-2" style="height: 5px;">
                <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" 
                     style="width: 100%"></div>
            </div>
        </div>
    @endif

    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">
                <i class="fas fa-globe-europe me-2"></i>Quiz des Capitales
            </h2>
        </div>
        
        <div class="card-body text-center py-5">
            @auth
                @if(!session('cooldown'))
                    <a href="{{ route('quiz.start') }}" class="btn btn-primary btn-lg px-5 py-3">
                        <i class="fas fa-play me-2"></i> Jouer maintenant
                    </a>
                    <div class="mt-3 text-muted">
                        <small>Vous avez 40 secondes pour répondre à 10 questions</small>
                    </div>
                @else
                    <button class="btn btn-secondary btn-lg px-5 py-3" disabled>
                        <i class="fas fa-clock me-2"></i> Quiz en cooldown
                    </button>
                    <div class="mt-3">
                        <a href="{{ route('game.history') }}" class="btn btn-outline-primary">
                            <i class="fas fa-history me-1"></i> Voir mes résultats
                        </a>
                    </div>
                @endif
            @else
                <div class="alert alert-warning w-75 mx-auto">
                    <i class="fas fa-info-circle me-2"></i>
                    Connectez-vous pour participer et sauvegarder vos scores
                </div>
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 py-3">
                    <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                </a>
            @endauth
        </div>
    </div>
</div>

@if(session('cooldown'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const endTime = {{ session('cooldown.expires_at') }};
            const timerElement = document.getElementById('countdown-timer');
            const alertElement = document.getElementById('cooldown-alert');
            const progressBar = document.querySelector('.progress-bar');
            
            function formatTime(seconds) {
                const hours = Math.floor(seconds / 3600);
                const mins = Math.floor((seconds % 3600) / 60);
                const secs = seconds % 60;
                return `${hours.toString().padStart(2, '0')}h ${mins.toString().padStart(2, '0')}m ${secs.toString().padStart(2, '0')}s`;
            }
            
            function updateTimer() {
                const now = Math.floor(Date.now() / 1000);
                const remaining = endTime - now;
                
                if (remaining <= 0) {
                    // Temps écoulé
                    clearInterval(timerInterval);
                    alertElement.classList.replace('alert-danger', 'alert-success');
                    alertElement.querySelector('h5').innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>Vous pouvez rejouer maintenant!
                    `;
                    timerElement.parentElement.innerHTML = '<span class="badge bg-success">Prêt à jouer!</span>';
                    progressBar.classList.replace('bg-danger', 'bg-success');
                    
                    // Recharger après 2 secondes pour voir le message
                    setTimeout(() => window.location.reload(), 2000);
                    return;
                }
                
                // Mise à jour du timer
                timerElement.textContent = formatTime(remaining);
                
                // Animation warning quand il reste moins de 5 minutes
                if (remaining < 300) {
                    timerElement.classList.add('bg-warning', 'text-dark');
                    timerElement.classList.remove('bg-dark');
                }
            }
            
            // Lancer le timer immédiatement puis toutes les secondes
            updateTimer();
            const timerInterval = setInterval(updateTimer, 1000);
        });
    </script>
@endif

<style>
    #cooldown-alert {
        border-left: 5px solid #dc3545;
        animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .progress-bar-animated {
        animation: progressAnimation 1s linear infinite;
    }
    
    @keyframes progressAnimation {
        0% { background-position: 0% 50%; }
        100% { background-position: 100% 50%; }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        #cooldown-alert .d-flex {
            flex-direction: column;
            text-align: center;
        }
        
        #cooldown-alert .ms-md-auto {
            margin-left: 0 !important;
            padding-left: 0 !important;
            margin-top: 1rem;
        }
    }
</style>
@endsection