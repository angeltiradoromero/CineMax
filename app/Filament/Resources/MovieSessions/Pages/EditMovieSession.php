<?php

namespace App\Filament\Resources\MovieSessions\Pages;

use App\Filament\Resources\MovieSessions\MovieSessionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMovieSession extends EditRecord
{
    protected static string $resource = MovieSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
