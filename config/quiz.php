<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Quiz Configuration
    |--------------------------------------------------------------------------
    |
    | Durée d'attente entre deux participations au quiz (en heures)
    |
    */
    'cooldown_hours' => env('QUIZ_COOLDOWN_HOURS', 1),
    
    /*
    | Durée du quiz en secondes
    */
    'duration' => env('QUIZ_DURATION', 40),
    
    /*
    | Nombre de questions par quiz
    */
    'questions_count' => env('QUIZ_QUESTIONS_COUNT', 10),

    'max_score' => env('QUIZ_MAX_SCORE', 100),
];