<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Movies\Movie;
use App\Services\Movies\MovieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class MovieController extends Controller
{
    // Muestra las películas que están en cartelera.
    public function getNowPlaying(MovieService $movieService)
    {
        $moviesBd = Movie::nowPlaying()->orderBy('release_date')->get();
        return view('movies.now-playing', compact('moviesBd'));
    }

    // Muestra las próximas películas que llegarán al cine.
    public function upcomingMovies(MovieService $movieService)
    {
        $moviesBd = Movie::upcomingMovies()->orderBy('release_date')->get();
        return view('movies.upcoming-movies', compact('moviesBd'));
    }

    // Muestra los detalles de una película y sus sesiones.
    public function movieDetails($id, MovieService $movieService)
    {
        $movieBd = Movie::findOrFail($id);
        $details = $movieService->movieDetails($movieBd->tmdb_id);
        $sessions = $movieService->getMovieSessions($movieBd->id);

        $movie = $details['movie'];
        $trailer = $details['trailer'];
        $videoType = $details['videoType'];

        return view('movies.movie-details', compact('movieBd', 'movie', 'trailer', 'videoType', 'sessions'));
    }

    // Busca películas para mostrar sugerencias.
    public function getSuggestions(Request $request)
    {
        $text = trim($request->search);

        $moviesBd = Movie::whereRaw("LOWER(title) LIKE ?", ['%' . strtolower($text) . '%'])->get();

        return response()->json($moviesBd);
    }

    // Realiza una búsqueda y muestra las películas encontradas.
    public function searchMovie(Request $request)
    {
        $text = $request->search;

        $moviesBd = Movie::whereRaw(
            "LOWER(title) LIKE ?",
            ['%' . mb_strtolower($text, 'UTF-8') . '%']
        )->get();

        return view('movies.search-results', compact('moviesBd', 'text'));
    }
}
