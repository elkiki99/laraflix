<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TmdbService extends Controller
{
    protected $token;

    public function __construct()
    {
        $this->token = config('services.tmdb.token');
    }

    public function getMovies($endpoint)
    {
        return Cache::remember($endpoint, 3600, function () use ($endpoint) {
            return Http::withToken($this->token)
                ->get("https://api.themoviedb.org/3/movie/{$endpoint}")
                ->json()['results'] ?? [];
        });
    }
    public function getSeries($endpoint)
    {
        return Cache::remember($endpoint, 3600, function () use ($endpoint) {
            return Http::withToken($this->token)
                ->get("https://api.themoviedb.org/3/tv/{$endpoint}")
                ->json()['results'] ?? [];
        });
    }
}
