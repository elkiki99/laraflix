<?php

// namespace App\Models;

// use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\Cache;

// class Movie /*extends Model*/
// {
//     public static function all()
//     {
//         return Cache::remember('popular_movies', 3600, function () {
//             return Http::withToken(config('services.tmdb.token'))
//                 ->get('https://api.themoviedb.org/3/movie/popular')
//                 ->json()['results'];
//         });
//     }

//     public static function find($id)
//     {
//         return Cache::remember("movie_{$id}", 3600, function () use ($id) {
//             return Http::withToken(config('services.tmdb.token'))
//                 ->get("https://api.themoviedb.org/3/movie/{$id}")
//                 ->json();
//         });
//     }

//     public static function withGenres()
//     {
//         $movies = self::all();
//         $genres = self::getGenres();

//         foreach ($movies as &$movie) {
//             $movie['genre_names'] = array_map(function ($genreId) use ($genres) {
//                 return $genres[$genreId] ?? 'Unknown';
//             }, $movie['genre_ids']);
//         }

//         return $movies;
//     }

//     private static function getGenres()
//     {
//         return Cache::remember('movie_genres', 3600, function () {
//             $genres = Http::withToken(config('services.tmdb.token'))
//                 ->get('https://api.themoviedb.org/3/genre/movie/list')
//                 ->json()['genres'];
//             return collect($genres)->pluck('name', 'id')->all();
//         });
//     }
// }
