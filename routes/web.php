<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PollController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Auth routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Poll routes
    Route::get('/polls', [PollController::class, 'index'])->name('polls.index');
    Route::get('/api/polls', [PollController::class, 'getPolls']);
    Route::get('/polls/{id}', [PollController::class, 'show']);
    Route::post('/polls/{id}/vote', [PollController::class, 'vote']);
    Route::post('/polls', [PollController::class, 'store']);
});

// Redirect root to polls or login
Route::get('/', function () {
    return redirect('/polls');
});
