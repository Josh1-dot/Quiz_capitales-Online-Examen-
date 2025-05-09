@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2><i class="bi bi-clock-history"></i> Historique de vos parties</h2>
        </div>
        
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h5>Meilleur score</h5>
                            <p class="display-6 text-success">{{ $stats['bestScore'] ?? '0' }}</p>
                        </div>
                    </div>
                </div>
                <!-- Ajoutez d'autres stats de la même manière -->
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Score</th>
                            <th>Réponses</th>
                            <th>Temps</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scores as $score)
                        <tr>
                            <td>{{ $score->created_at->isoFormat('LL HH:mm') }}</td> {{-- 15 avril 2025 13:59 --}}
                            <td class="{{ $score->score >= 80 ? 'text-success' : '' }}">
                                {{ number_format($score->score, 1) }}%
                            </td>
                            <td>
                                <progress class="w-100" 
                                          value="{{ $score->correct_answers }}" 
                                          max="{{ $score->total_questions }}">
                                    {{ $score->correct_answers }}/{{ $score->total_questions }}
                                </progress>
                            </td>
                            <td>{{ $score->time_seconds ? $score->time_seconds.' sec' : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <i class="fas fa-gamepad me-2"></i>Commencez une partie !
                            </td>
                        </tr>
                    @endforelse
                    
                    {{ $scores->links() }} {{-- Pagination --}}
                    </tbody>
                </table>
                {{ $scores->links() }}
            </div>
        </div>
    </div>
</div>
@endsection