<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\Movies\Movie;
use App\Services\Movies\FavoriteService;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Añadir o quitar una película de favoritos
    public function toggleFavorite(Movie $movie, FavoriteService $favoriteService)
    {
        $user = Auth::user();
        $added = $favoriteService->toggleFavorite($user, $movie);

        if ($added) {
            return response()->json([
                'success' => true,
                'added' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'added' => false,
        ]);
    }

    // Mostrar las películas favoritas del usuario
    public function listFavorites(FavoriteService $favoriteService)
    {
        $favorites = $favoriteService->getFavorites(Auth::user());
        return view('favorites.list', compact('favorites'));
    }
}
