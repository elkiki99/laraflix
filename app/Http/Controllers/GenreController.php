<?php

namespace App\Http\Controllers;

class GenreController extends Controller
{
    public function moviesShow($genreId)
    {
        return view('genres.show-movies', [
            'genreId' => $genreId,
        ]);
    }

    public function seriesShow($genreId)
    {
        return view('genres.show-series', [
            'genreId' => $genreId,
        ]);
    }
}