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
    Route::get('/polls/{id}/results', [\App\Http\Controllers\ResultsController::class, 'show']);

    // Admin routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/polls/{id}/votes', [\App\Http\Controllers\AdminController::class, 'viewVotes']);
        Route::post('/polls/{pollId}/release/{ip}', [\App\Http\Controllers\AdminController::class, 'releaseIp']);
        Route::get('/polls/{pollId}/audit/{ip}', [\App\Http\Controllers\AdminController::class, 'auditTrail']);
    });
});

// Redirect root to polls or login
Route::get('/', function () {
    return redirect('/polls');
});
