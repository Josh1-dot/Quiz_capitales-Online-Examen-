 @extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Hero Section -->
    <div class="hero-section bg-light p-5 rounded-3 mb-5 text-center">
        <h1 class="display-4 fw-bold text-primary mb-3">Quiz des Capitales</h1>
        <p class="lead mb-4">Testez vos connaissances géographiques en un clic !</p>
        <a href="{{ route('game.play') }}" class="btn btn-success btn-lg px-5">
            <i class="bi bi-play-fill me-2"></i> Jouer maintenant
        </a>
    </div>

    <!-- Features Grid -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <i class="bi bi-globe text-primary fs-1 mb-3"></i>
                    <h3 class="h4">200+ Pays</h3>
                    <p>Toutes les capitales du monde à découvrir</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <i class="bi bi-graph-up text-primary fs-1 mb-3"></i>
                    <h3 class="h4">Suivi Progrès</h3>
                    <p>Visualisez votre amélioration dans le temps</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <i class="bi bi-trophy text-primary fs-1 mb-3"></i>
                    <h3 class="h4">Classement</h3>
                    <p>Défiez vos amis et montez dans le top</p>
                </div>
            </div>
        </div>
    </div>

    <!-- User Dashboard -->
    <div class="card shadow border-0 mb-5">
        <div class="card-header bg-primary text-white">
            <h2 class="h5 mb-0"><i class="bi bi-speedometer2 me-2"></i> Tableau de bord</h2>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="p-4 bg-light rounded-3 h-100">
                        <h3 class="h4 mb-3">Votre dernière partie</h3>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Score :</span>
                            <strong>15/20</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Classement :</span>
                            <strong>#42</strong>
                        </div>
                        <a href="{{ route('game.play') }}" class="btn btn-primary w-100">
                            <i class="bi bi-arrow-repeat me-2"></i> Rejouer
                        </a>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="p-4 bg-light rounded-3 h-100">
                        <h3 class="h4 mb-3">Statistiques</h3>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Parties jouées :</span>
                            <strong>27</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Meilleur score :</span>
                            <strong>18/20</strong>
                        </div>
                        <a href="{{ route('game.history') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-clock-history me-2"></i> Historique complet
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="text-center py-4 bg-light rounded-3">
        <h2 class="h3 mb-3">Prêt à relever le défi ?</h2>
        <a href="{{ route('game.play') }}" class="btn btn-success btn-lg px-5">
            <i class="bi bi-lightning-charge-fill me-2"></i> Démarrer un quiz
        </a>
    </div>
</div>
@endsection