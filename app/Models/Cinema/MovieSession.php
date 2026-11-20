<?php

namespace App\Models\Cinema;

use App\Models\Movies\Movie;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['movie_id', 'room_id', 'date', 'start_time', 'price'])]

class MovieSession extends Model
{
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
