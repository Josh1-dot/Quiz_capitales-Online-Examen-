@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('images/default-avatar.png') }}" 
                         class="rounded-circle mb-3" 
                         width="150" 
                         height="150"
                         alt="Avatar">
                    
                    <h3>{{ $user->name }}</h3>
                    <p class="text-muted">{{ $user->email }}</p>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil-square"></i> Modifier le profil
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-award"></i> Badges</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="badge bg-warning text-dark mb-2 p-2">
                            <i class="bi bi-trophy-fill fs-4"></i>
                            <div>Explorateur</div>
                        </div>
                        <!-- Ajoutez d'autres badges -->
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h6>Parties jouées</h6>
                                <h3 class="text-primary">{{ $stats['totalGames'] }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h6>Meilleur score</h6>
                                <h3 class="text-success">{{ $stats['bestScore'] }}%</h3>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h6>Réussite</h6>
                                <h3 class="text-info">
                                    {{ $stats['totalQuestions'] > 0 
                                        ? round(($stats['totalCorrect']/$stats['totalQuestions'])*100, 1) 
                                        : 0 }}%
                                </h3>
                            </div>
                        </div>
                    </div>
                    
                    <canvas id="progressChart" height="100"></canvas>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Dernières parties</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Score</th>
                                    <th>Réponses</th>
                                    <th>Détails</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentScores as $score)
                                <tr>
                                    <td>{{ $score->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $score->score >= 80 ? 'success' : ($score->score >= 50 ? 'warning' : 'danger') }}">
                                            {{ $score->score }}%
                                        </span>
                                    </td>
                                    <td>{{ $score->correct_answers }}/{{ $score->total_questions }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucune partie jouée encore</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Graphique de progression
    const ctx = document.getElementById('progressChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($user->scores->pluck('created_at')->map(fn($date) => $date->format('d/m')) !!},
            datasets: [{
                label: 'Vos scores',
                data: {!! json_encode($user->scores->pluck('score')) !!},
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
@endpush
@endsection