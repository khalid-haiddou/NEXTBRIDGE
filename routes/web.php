<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;

// Authentication Routes
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Handle logout
Route::get('/login', [AuthController::class, 'showLoginRegister'])->name('login'); // Show login/register form
Route::post('/register', [AuthController::class, 'register'])->name('register'); // Handle registration
Route::post('/login', [AuthController::class, 'login'])->name('doLogin'); // Handle login
Route::middleware(['auth'])->group(function () {
});


// Routes secured for authenticated users
Route::middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [ParticipatesController::class, 'dashboard'])->name('dashboard');

    
});
