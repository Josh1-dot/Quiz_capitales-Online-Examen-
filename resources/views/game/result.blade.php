@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Résultats du Quiz</h2>
            <div class="float-end">
                <span class="badge bg-light text-dark fs-6">
                    {{ $percentage }}%
                </span>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Score Summary -->
            <div class="alert {{ $percentage >= 70 ? 'alert-success' : ($percentage >= 50 ? 'alert-warning' : 'alert-danger') }}">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="alert-heading mb-1">
                            Score : {{ $score }}/{{ $total }}
                        </h4>
                        <p class="mb-0">
                            @if($percentage >= 80)
                                Excellent ! 🎉
                            @elseif($percentage >= 60)
                                Bien joué ! 👍
                            @else
                                Continuez à pratiquer ! 💪
                            @endif
                        </p>
                    </div>
                    <div class="text-end">
                        <div class="progress" style="width: 100px; height: 20px;">
                            <div class="progress-bar bg-{{ $percentage >= 70 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger') }}" 
                                 role="progressbar" 
                                 style="width: {{ $percentage }}%" 
                                 aria-valuenow="{{ $percentage }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        <small class="text-muted">{{ $percentage }}% de réussite</small>
                    </div>
                </div>
            </div>

            <!-- Results Details -->
            <div class="results-list mt-4">
                <h5 class="mb-3">Détail des réponses :</h5>
                
                @foreach($results as $index => $result)
                <div class="card mb-3 border-{{ $result['is_correct'] ? 'success' : 'danger' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title mb-3">
                                #{{ $index + 1 }} - {{ $result['country'] }}
                            </h5>
                            <span class="badge bg-{{ $result['is_correct'] ? 'success' : 'danger' }}">
                                {{ $result['is_correct'] ? '✓ Correct' : '✗ Incorrect' }}
                            </span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="answer-box {{ !$result['is_correct'] ? 'bg-light-danger' : '' }}">
                                    <p class="mb-1"><strong>Votre réponse :</strong></p>
                                    <p class="fs-5">{{ $result['user_answer'] }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if(!$result['is_correct'])
                                <div class="answer-box bg-light-success">
                                    <p class="mb-1"><strong>Bonne réponse :</strong></p>
                                    <p class="fs-5">{{ $result['correct_answer'] }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('game.play') }}" class="btn btn-primary btn-lg px-4">
                    <i class="fas fa-redo me-2"></i> Nouveau Quiz
                </a>
                <div>
                    <a href="{{ route('game.history') }}" class="btn btn-info me-2">
                        <i class="fas fa-history me-2"></i> Historique
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i> Accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .answer-box {
        padding: 10px;
        border-radius: 5px;
    }
    .bg-light-danger {
        background-color: #fff5f5;
    }
    .bg-light-success {
        background-color: #f0fff4;
    }
</style>
@endsection