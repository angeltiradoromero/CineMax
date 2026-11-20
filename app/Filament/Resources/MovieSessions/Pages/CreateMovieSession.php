<?php

namespace App\Filament\Resources\MovieSessions\Pages;

use App\Filament\Resources\MovieSessions\MovieSessionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMovieSession extends CreateRecord
{
    protected static string $resource = MovieSessionResource::class;
}
