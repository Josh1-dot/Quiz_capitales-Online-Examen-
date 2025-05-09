<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{
    /**
     * Constructeur avec middleware
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['check', 'history', 'leaderboard']);
        $this->middleware('quiz.replay')->only(['play']); // Nouveau middleware
    }
    
    /**
     * Affiche le quiz avec 10 pays aléatoires
     */
    public function play()
    {
        $countries = Country::select(['id', 'name', 'capital'])
            ->inRandomOrder()
            ->limit(10)
            ->get();
        
        if ($countries->isEmpty()) {
            Log::error('Aucun pays disponible dans la base de données');
            return back()->with('error', 'Aucun pays disponible pour le quiz');
        }

        return view('game.play', [
            'countries' => $countries,
            'questionCount' => $countries->count()
        ]);
    }

    /**
     * Traite les réponses du quiz
     */
    public function check(Request $request)
    {
        $validated = $request->validate([
            'answers' => 'required|array',
            'time_used' => 'required|integer'
        ]);

        $results = [];
        $correctCount = 0;
        $countries = Country::whereIn('id', array_keys($validated['answers']))->get()->keyBy('id');

        // Garantir qu'on traite toujours 10 questions
        $allCountries = Country::inRandomOrder()->limit(10)->get();
        
        foreach ($allCountries as $country) {
            $userAnswer = $validated['answers'][$country->id] ?? null;
            $isCorrect = ($userAnswer !== null) 
                ? $this->compareAnswers($userAnswer, $country->capital)
                : false;

            $results[] = [
                'country' => $country->name,
                'user_answer' => $userAnswer ?? 'Non répondu',
                'correct_answer' => $country->capital,
                'is_correct' => $isCorrect
            ];

            if ($isCorrect) {
                $correctCount++;
            }
        }

        // Calcul du score sur 10 (1 point par bonne réponse)
        $score = $correctCount;
        $percentage = ($score / 10) * 100;

        // Enregistrement du score si utilisateur connecté
        if (Auth::check()) {
            $this->saveScore(Auth::user(), $score, 10, $validated['time_used']);
        }

        return view('game.result', [
            'results' => $results,
            'score' => $score,
            'total' => 10, // Toujours sur 10 questions
            'percentage' => $percentage,
            'time_used' => $validated['time_used']
        ]);
    }

    /**
     * Compare les réponses en ignorant la casse/accents
     */
    protected function compareAnswers(string $userAnswer, string $correctAnswer): bool
    {
        return $this->normalizeString($userAnswer) === $this->normalizeString($correctAnswer);
    }
    
    /**
     * Normalise les chaînes pour comparaison
     */
    protected function normalizeString(string $input): string
    {
        return Str::lower(Str::ascii(trim($input)));
    }

    /**
     * Sauvegarde le score de l'utilisateur
     */
    protected function saveScore(User $user, int $score, int $totalQuestions, int $timeUsed): void
    {
        $user->scores()->create([
            'score' => ($score / $totalQuestions) * 100,
            'correct_answers' => $score,
            'total_questions' => $totalQuestions,
            'time_seconds' => $timeUsed,
            'feedback' => null // Champ pour les commentaires futurs
        ]);
    }

    /**
     * Affiche l'historique des scores
     */
    public function history()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $scores = $user->scores()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'bestScore' => $user->scores()->max('score'),
            'averageScore' => round($user->scores()->avg('score'), 1),
            'totalGames' => $user->scores()->count()
        ];

        return view('game.history', [
            'scores' => $scores,
            'stats' => $stats
        ]);
    }

    /**
     * Affiche le classement
     */
    public function leaderboard()
    {
        $daily = Score::whereDate('created_at', today())
            ->selectRaw('user_id, MAX(score) as max_score')
            ->groupBy('user_id')
            ->orderBy('max_score', 'desc')
            ->with('user')
            ->take(10)
            ->get();

        $allTime = Score::selectRaw('user_id, MAX(score) as max_score')
            ->groupBy('user_id')
            ->orderBy('max_score', 'desc')
            ->with('user')
            ->take(10)
            ->get();

        return view('game.leaderboard', compact('daily', 'allTime'));
    }
}