<?php

namespace App\Services\Movies;

use App\Models\Cinema\MovieSession;
use App\Models\Movies\Movie;
use Illuminate\Support\Facades\Http;

class MovieService
{
    // Obtener y guardar las películas que están en cartelera.
    public function getNowPlaying()
    {
        $response = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.url') . '/movie/now_playing', [
                'language' => 'es-ES',
                'region' => 'ES',
            ]);

        $data = $response->json();

        if (isset($data['results'])) {

            $movies = $data['results'];

            foreach ($movies as $movie) {

                $overview = $movie['overview'];

                $details = Http::withToken(config('services.tmdb.token'))
                    ->get(config('services.tmdb.url') . '/movie/' . $movie['id'], [
                        'language' => 'es-ES',
                    ]);

                $genre = collect($details->json('genres', []))
                    ->pluck('name')
                    ->toArray();

                // Crear la película o actualizar sus datos si ya existe.
                Movie::updateOrCreate(
                    ['tmdb_id' => $movie['id']],
                    [
                        'title' => $movie['title'],
                        'overview' => $overview,
                        'genre' => $genre,
                        'runtime' => $details['runtime'],
                        'release_date' => $movie['release_date'],
                        'poster_path' => $movie['poster_path'],
                        'backdrop_path' => $movie['backdrop_path'],
                        'tmdb_status' => $details['status'],
                        'status' => 'now_playing',
                    ]
                );
            }

            return true;
        }

        return false;
    }

    // Obtener y guardar las próximas películas.
    public function getUpcomingMovies()
    {
        $response = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.url') . '/movie/upcoming', [
                'language' => 'es-ES',
                'region' => 'ES',
            ]);

        $data = $response->json();

        if (isset($data['results'])) {

            $movies = $data['results'];

            foreach ($movies as $movie) {

                $overview = $movie['overview'];

                $details = Http::withToken(config('services.tmdb.token'))
                    ->get(config('services.tmdb.url') . '/movie/' . $movie['id'], [
                        'language' => 'es-ES',
                    ]);

                // Si no hay sinopsis en español, se intenta obtener en inglés.
                if (empty($overview)) {

                    $responseIng = Http::withToken(config('services.tmdb.token'))
                        ->get(config('services.tmdb.url') . '/movie/' . $movie['id'], [
                            'language' => 'en-US',
                        ]);

                    $dataIng = $responseIng->json();

                    $overview = $dataIng['overview'];
                }

                $genre = collect($details->json('genres', []))
                    ->pluck('name')
                    ->toArray();

                Movie::updateOrCreate(
                    ['tmdb_id' => $movie['id']],
                    [
                        'title' => $movie['title'],
                        'overview' => $overview,
                        'genre' => $genre,
                        'runtime' => $details['runtime'],
                        'release_date' => $movie['release_date'],
                        'poster_path' => $movie['poster_path'],
                        'backdrop_path' => $movie['backdrop_path'],
                        'tmdb_status' => $details['status'],
                        'status' => 'upcoming',
                    ]
                );
            }

            return true;
        }

        return false;
    }

    // Obtener la información de una película y buscar su tráiler.
    public function movieDetails($tmdbId)
    {
        $response = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.url') . '/movie/' . $tmdbId, [
                'language' => 'es-ES',
                'region' => 'ES',
                'append_to_response' => 'videos',
            ]);

        $data = $response->json();

        $movie = isset($data['id']) ? $data : [];

        $videoTypes = [
            'Trailer',
            'Teaser',
            'Clip',
            'Featurette',
            'Behind the Scenes',
            'Bloopers',
            'Opening Credits',
            'Recap'
        ];

        $videoType = null;
        $trailer = null;

        $videos = $data['videos']['results'] ?? [];

        // Buscar el primer vídeo de YouTube según el tipo indicado.
        foreach ($videoTypes as $type) {

            foreach ($videos as $video) {

                if ($video['type'] === $type && $video['site'] === "YouTube") {

                    $trailer = $video['key'];
                    $videoType = $video['type'];

                    break 2;
                }
            }
        }

        return [
            'movie' => $movie,
            'trailer' => $trailer,
            'videoType' => $videoType
        ];
    }

    // Obtener las próximas sesiones de una película.
    public function getMovieSessions($movieId)
    {
        return MovieSession::where('movie_id', $movieId)
            ->whereDate('date', '>=', now()->toDateString())
            ->with('room')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
    }
}
