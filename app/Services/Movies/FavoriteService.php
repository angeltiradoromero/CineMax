<?php

namespace App\Services\Movies;

use App\Models\Movies\Movie;
use App\Models\User;

class FavoriteService
{
    // Añadir película a favoritos
    public function addFavorite(User $user, Movie $movie): void
    {
        $user->favorites()->syncWithoutDetaching([$movie->id]);
    }

    // Eliminar película de favoritos
    public function removeFavorite(User $user, Movie $movie): void
    {
        $user->favorites()->detach($movie->id);
    }

    // Comprobar si una película es favorita del usuario
    public function isFavorite(User $user, Movie $movie): bool
    {
        return $user->favorites()->where('movies.id', $movie->id)->exists();
    }

    // Obtener películas favoritas del usuario
    public function getFavorites(User $user)
    {
        return $user->favorites()->orderBy('release_date')->get();
    }

    // Contar cuántos usuarios tienen una película en favoritos
    public function getTotalFavorites(Movie $movie): int
    {
        return $movie->favoritedBy()->count();
    }

    // Añadir o eliminar una película
    public function toggleFavorite(User $user, Movie $movie): bool
    {
        if ($this->isFavorite($user, $movie)) {
            $this->removeFavorite($user, $movie);
            return false;
        }

        $this->addFavorite($user, $movie);
        return true;
    }
}
