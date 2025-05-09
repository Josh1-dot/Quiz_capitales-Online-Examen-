@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Modifier le profil</h5>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Section Avatar -->
                        <div class="row mb-4">
                            <label for="avatar" class="col-md-4 col-form-label text-md-end">Avatar</label>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="position-relative">
                                        <img src="{{ $user->avatar_url }}" 
                                             class="rounded-circle me-3" 
                                             width="100" 
                                             height="100"
                                             alt="Avatar actuel"
                                             id="avatarPreview">
                                        @if($user->avatar)
                                        <button type="button" 
                                                class="btn btn-danger btn-sm position-absolute top-0 start-0 rounded-circle"
                                                onclick="confirmDeleteAvatar()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <input type="file" 
                                       class="form-control @error('avatar') is-invalid @enderror" 
                                       id="avatar" 
                                       name="avatar"
                                       accept="image/*"
                                       onchange="previewAvatar(this)">
                                @error('avatar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="text-muted">Formats acceptés: JPG, PNG (max 2MB)</small>
                            </div>
                        </div>

                        <!-- Section Informations -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Nom</label>
                            <div class="col-md-6">
                                <input id="name" type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required
                                       autocomplete="name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required
                                       autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Section Mot de passe -->
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Nouveau mot de passe</label>
                            <div class="col-md-6">
                                <input id="password" type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password"
                                       autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="text-muted">Laissez vide pour ne pas modifier</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Confirmation</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" 
                                       class="form-control" 
                                       name="password_confirmation"
                                       autocomplete="new-password">
                            </div>
                        </div>

                        <!-- Boutons de soumission -->
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-circle me-2"></i> Enregistrer
                                </button>
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-x-circle me-2"></i> Annuler
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Formulaire caché pour la suppression d'avatar -->
                    <form id="deleteAvatarForm" action="{{ route('profile.avatar.destroy') }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Prévisualisation de l'avatar
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Confirmation suppression avatar
    function confirmDeleteAvatar() {
        if (confirm('Supprimer votre avatar ?')) {
            document.getElementById('deleteAvatarForm').submit();
        }
    }
</script>
@endpush
@endsection