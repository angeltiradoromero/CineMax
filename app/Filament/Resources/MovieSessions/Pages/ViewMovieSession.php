<?php

namespace App\Filament\Resources\MovieSessions\Pages;

use App\Filament\Resources\MovieSessions\MovieSessionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMovieSession extends ViewRecord
{
    protected static string $resource = MovieSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
