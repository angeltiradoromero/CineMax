<?php

namespace App\Models\Movies;

use App\Models\Cinema\MovieSession;
use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['tmdb_id', 'title', 'overview', 'runtime', 'genre', 'certification', 'director', 'poster_path', 'backdrop_path', 'release_date', 'tmdb_status', 'status'])]

class Movie extends Model
{
    public function scopeNowPlaying(Builder $query): Builder
    {
        return $query->where('status', 'now_playing');
    }

    public function scopeUpcomingMovies(Builder $query): Builder
    {
        return $query->where('status', 'upcoming');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(MovieSession::class);
    }

    protected $casts = [
        'genre' => 'array',
    ];
}
