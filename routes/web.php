<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;

Route::view('/', 'welcome');

Route::get('/movies', [MovieController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('movies.index');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
