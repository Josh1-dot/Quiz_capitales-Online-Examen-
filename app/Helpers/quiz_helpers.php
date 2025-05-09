<?php

if (!function_exists('normalize_answer')) {
    /**
     * Normalise les réponses pour la comparaison
     */
    function normalize_answer(string $answer): string
    {
        return mb_strtolower(trim($answer));
    }
}