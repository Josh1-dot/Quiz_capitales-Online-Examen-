<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Score;

class ProfileController extends Controller
{
    /**
     * Affiche le profil utilisateur
     * 
     * @return \Illuminate\View\View
     */
    public function show()
    {
        /** @var User $user */
        $user = Auth::user();

        /** @var \Illuminate\Database\Eloquent\Collection<Score> $recentScores */
        $recentScores = $user->scores()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $stats = [
            'totalGames' => $user->scores()->count(),
            'bestScore' => $user->scores()->max('score'),
            'averageScore' => round($user->scores()->avg('score'), 1),
            'totalCorrect' => $user->scores()->sum('correct_answers'),
            'totalQuestions' => $user->scores()->sum('total_questions'),
            'successRate' => $user->scores()->sum('total_questions') > 0
                ? round(($user->scores()->sum('correct_answers') / $user->scores()->sum('total_questions')) * 100, 1)
                : 0,
        ];

        return view('profile.show', compact('user', 'recentScores', 'stats'));
    }

    /**
     * Affiche le formulaire d'édition du profil
     * 
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Met à jour le profil utilisateur
     * 
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::delete('public/avatars/' . $user->avatar);
            }

            $filename = $user->id . '-' . time() . '.' . $request->avatar->extension();
            $request->avatar->storeAs('public/avatars', $filename);
            $updateData['avatar'] = $filename;
        }

        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('profile.show')
            ->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Supprime l'avatar de l'utilisateur
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyAvatar()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->avatar) {
            Storage::delete('public/avatars/' . $user->avatar);
            $user->update(['avatar' => null]);

            return back()->with('success', 'Avatar supprimé avec succès !');
        }

        return back()->with('error', 'Aucun avatar à supprimer !');
    }
}