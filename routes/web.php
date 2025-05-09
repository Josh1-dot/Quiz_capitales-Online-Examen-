<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Routes publiques
Route::get('/', function () {
    return view('home');
})->name('homepage');

// Routes d'authentification
Route::group(['namespace' => 'Auth'], function () {
    // Authentication
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    // Registration
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    
    // Password Reset
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Routes protégées par auth
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/avatar', [ProfileController::class, 'destroyAvatar'])->name('avatar.destroy');
        Route::get('/history', [ProfileController::class, 'history'])->name('history');
    });
    
    // Game Routes
    Route::prefix('game')->name('game.')->group(function () {
        Route::get('/', [GameController::class, 'play'])->name('play');
        Route::post('/check', [GameController::class, 'check'])->name('check');
        Route::get('/history', [GameController::class, 'history'])->name('history');
        Route::get('/leaderboard', [GameController::class, 'leaderboard'])->name('leaderboard');
    });
    
    // Quiz Route
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
});

// Redirections
Route::redirect('/play', '/game')->name('play.redirect');
Route::redirect('/dashboard', '/home')->name('dashboard');