<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['avatar_url'];

    /**
     * Relation avec les scores (alias de quizAttempts pour compatibilité)
     */
    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    /**
     * Relation avec les tentatives de quiz (alias de scores)
     */
    public function quizAttempts(): HasMany
    {
        return $this->scores();
    }

    /**
     * Dernier score réalisé
     */
    public function lastScore(): ?Score
    {
        return $this->scores()->latest()->first();
    }

    /**
     * Vérifie si l'utilisateur a déjà joué récemment
     */
    public function hasPlayedRecently(): bool
    {
        $cooldownHours = (int)config('quiz.cooldown_hours', 1);
        if ($cooldownHours <= 0) return false;

        $lastAttempt = $this->lastScore();
        if (!$lastAttempt) return false;

        return $lastAttempt->created_at->addHours($cooldownHours)->isFuture();
    }

    /**
     * Temps restant avant de pouvoir rejouer
     */
    public function cooldownRemaining(): ?array
    {
        if (!$this->hasPlayedRecently()) return null;

        $lastScore = $this->lastScore();
        if (!$lastScore) return null;

        $cooldownHours = (int)config('quiz.cooldown_hours', 1);
        $availableAt = $lastScore->created_at->addHours($cooldownHours);
        $remaining = now()->diff($availableAt);

        return [
            'message' => 'Vous devez attendre avant de rejouer',
            'remaining' => $remaining->format('%Hh %Im %Ss'),
            'available_at' => $availableAt->format('H:i'),
            'expires_at' => $availableAt->timestamp
        ];
    }

    /**
     * URL de l'avatar
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return str_starts_with($this->avatar, 'http') 
                ? $this->avatar 
                : asset('storage/'.$this->avatar);
        }
        
        return asset('images/default-avatar.png');
    }
}