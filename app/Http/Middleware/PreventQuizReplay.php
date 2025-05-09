<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PreventQuizReplay
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        // Vérification plus robuste avec interface
        if ($user && $user instanceof \App\Models\User && $user->hasPlayedRecently()) {
            $lastAttempt = $user->quizAttempts()->latest()->first();

            if ($lastAttempt) {
                $cooldownHours = (int)config('quiz.cooldown_hours', 1);
                $nextAvailable = $lastAttempt->created_at->addHours($cooldownHours);
                $remainingTime = Carbon::now()->diff($nextAvailable);

                return redirect()->route('quiz.index')->with('cooldown', [
                    'message' => "Désolé, vous n'êtes pas autorisé à repasser l'examen immédiatement après l'avoir remis. Vous devez attendre 1 heure avant de pouvoir le refaire. Pour des raisons d'équité, un délai d'attente (d'une heure) est requis entre deux tentatives d'examen. Merci de votre compréhension. ",
                    'remaining' => $remainingTime->format('%Hh %Im %Ss'),
                    'expires_at' => $nextAvailable->timestamp,
                    'available_at' => $nextAvailable->format('H:i')
                ]);
            }
        }

        return $next($request);
    }
}