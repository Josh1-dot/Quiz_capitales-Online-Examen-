<?php<?php

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
    }
    
    /**
     * Affiche le quiz avec 10 pays alÃ©atoires
     */
    public function play()
    {
        $countries = Country::select(['id', 'name', 'capital'])
            ->inRandomOrder()
            ->limit(10)
            ->get();
        
        if ($countries->isEmpty()) {
            Log::error('Aucun pays disponible dans la base de donnÃ©es');
            return back()->with('error', 'Aucun pays disponible pour le quiz');
        }

        return view('game.play', [
            'countries' => $countries,
            'questionCount' => $countries->count()
        ]);
    }

    /**
     * Traite les rÃ©ponses du quiz
     */
    public function check(Request $request)
    {
        $validated = $request->validate([
            'answers' => 'required|array|min:1',
            'answers.*' => 'required|string|max:255'
        ]);

        $results = collect();
        $correctCount = 0;

        foreach ($validated['answers'] as $countryId => $userAnswer) {
            $country = Country::findOrFail($countryId);
            $isCorrect = $this->compareAnswers($userAnswer, $country->capital);

            $results->push([
                'country' => $country->name,
                'user_answer' => $userAnswer,
                'correct_answer' => $country->capital,
                'is_correct' => $isCorrect
            ]);

            if ($isCorrect) {
                $correctCount++;
            }
        }

        $totalQuestions = $results->count();
        $percentage = round(($correctCount / $totalQuestions) * 100);

        // Enregistrement du score si utilisateur connectÃ©
        if (Auth::check()) {
            $this->saveScore(Auth::user(), $correctCount, $totalQuestions);
        }

        return view('game.result', [
            'results' => $results,
            'score' => $correctCount,
            'total' => $totalQuestions,
            'percentage' => $percentage
        ]);
    }

    /**
     * Compare les rÃ©ponses en ignorant la casse/accents
     */
    protected function compareAnswers(string $userAnswer, string $correctAnswer): bool
    {
        return $this->normalizeString($userAnswer) === $this->normalizeString($correctAnswer);
    }
    
    /**
     * Normalise les chaÃ®nes pour comparaison
     */
    protected function normalizeString(string $input): string
    {
        return Str::lower(Str::ascii(trim($input)));
    }

    /**
     * Sauvegarde le score de l'utilisateur
     */
    protected function saveScore(User $user, int $correctCount, int $totalQuestions = 10): void
    {
        $user->scores()->create([
            'score' => ($correctCount / $totalQuestions) * 100,
            'correct_answers' => $correctCount,
            'total_questions' => $totalQuestions
            // 'time_seconds' => null // RetirÃ© car colonne non existante
        ]);
    }

    /**
     * Affiche l'historique des scores
     */

     public function history()
     {
         $userId = auth()->id();
         
         $scores = Score::where('user_id', $userId)
             ->orderBy('created_at', 'desc')
             ->paginate(10);
     
         $stats = [
             'bestScore' => Score::where('user_id', $userId)->max('score'),
             'averageScore' => round(Score::where('user_id', $userId)->avg('score'), 1),
             'totalGames' => Score::where('user_id', $userId)->count()
         ];
     
         return view('game.history', compact('scores', 'stats'));
     }


    // public function history()
    // {
    //     $user = auth()->user();
        
    //     $scores = $user->scores()
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(10);

    //     $stats = [
    //         'bestScore' => $user->scores()->max('score'),
    //         'averageScore' => round($user->scores()->avg('score'), 1),
    //         'totalGames' => $user->scores()->count(),
    //         'averageTime' => null // RetirÃ© car time_seconds non disponible
    //     ];

    //     return view('game.history', [
    //         'scores' => $scores,
    //         'stats' => $stats
    //     ]);
    

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
