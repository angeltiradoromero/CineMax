<?php

namespace App\Models\Cinema;

use App\Models\Cinema\MovieSession;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'capacity', 'is_active'])]
class Room extends Model
{
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function sessions()
    {
        return $this->hasMany(MovieSession::class);
    }
}
