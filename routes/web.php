<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\GenreController;

Route::get('/movies', [MovieController::class, 'index'])->middleware(['auth', 'verified'])->name('movies.index');
Route::get('/movie/{id}', [MovieController::class, 'show'])->middleware(['auth', 'verified'])->name('movies.show');
Route::get('/movies/genre/{genreId}', [GenreController::class, 'moviesShow'])->middleware(['auth', 'verified'])->name('movies.genres');

Route::get('/series', [SeriesController::class, 'index'])->middleware(['auth', 'verified'])->name('series.index');
Route::get('/series/{id}', [SeriesController::class, 'show'])->middleware(['auth', 'verified'])->name('series.show');
Route::get('/series/genre/{genreId}', [GenreController::class, 'seriesShow'])->middleware(['auth', 'verified'])->name('series.genres');


Route::view('/', 'home')->middleware(['auth', 'verified'])->name('home');
Route::view('profile', 'profile')->middleware(['auth'])->name('profile');
Route::view('laraflix', 'laraflix')->middleware(['auth'])->name('laraflix');
Route::view('search', 'search')->middleware(['auth'])->name('search');
Route::view('watchlist', 'watchlist')->middleware(['auth'])->name('watchlist');

require __DIR__ . '/auth.php';
