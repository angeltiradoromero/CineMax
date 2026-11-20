<?php

namespace App\Filament\Resources\MovieSessions\Pages;

use App\Filament\Resources\MovieSessions\MovieSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMovieSessions extends ListRecords
{
    protected static string $resource = MovieSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
