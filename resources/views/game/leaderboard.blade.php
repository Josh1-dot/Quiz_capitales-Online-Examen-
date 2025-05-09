@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h3><i class="bi bi-trophy"></i> Classement du jour</h3>
                </div>
                <div class="card-body">
                    <ol class="list-group list-group-numbered">
                        @foreach($daily as $index => $score)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">{{ $score->user->name }}</div>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $score->max_score }} pts</span>
                        </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h3><i class="bi bi-trophy-fill"></i> Classement général</h3>
                </div>
                <div class="card-body">
                    <ol class="list-group list-group-numbered">
                        @foreach($allTime as $index => $score)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">{{ $score->user->name }}</div>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $score->max_score }} pts</span>
                        </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection