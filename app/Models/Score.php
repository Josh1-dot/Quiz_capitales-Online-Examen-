<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'score',
        'correct_answers',
        'total_questions',
         
    ];

    protected $casts = [
        'played_at' => 'datetime'
    ];

    protected $appends = ['success_percentage'];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calcul du pourcentage de réussite
     */
    public function getSuccessPercentageAttribute(): float
    {
        return $this->total_questions > 0
            ? round(($this->correct_answers / $this->total_questions) * 100, 2)
            : 0;
    }

    /**
     * Formatage du temps passé
     */
    public function getTimeFormattedAttribute(): string
    {
        $minutes = floor($this->time_seconds / 60);
        $seconds = $this->time_seconds % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}