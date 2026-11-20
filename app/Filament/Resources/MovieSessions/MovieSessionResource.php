<?php

namespace App\Filament\Resources\MovieSessions;

use App\Filament\Resources\MovieSessions\Pages\CreateMovieSession;
use App\Filament\Resources\MovieSessions\Pages\EditMovieSession;
use App\Filament\Resources\MovieSessions\Pages\ListMovieSessions;
use App\Filament\Resources\MovieSessions\Pages\ViewMovieSession;
use App\Filament\Resources\MovieSessions\Schemas\MovieSessionForm;
use App\Filament\Resources\MovieSessions\Schemas\MovieSessionInfolist;
use App\Filament\Resources\MovieSessions\Tables\MovieSessionsTable;
use App\Models\Cinema\MovieSession;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MovieSessionResource extends Resource
{
    protected static ?string $model = MovieSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'date';

    public static function form(Schema $schema): Schema
    {
        return MovieSessionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MovieSessionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MovieSessionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMovieSessions::route('/'),
            'create' => CreateMovieSession::route('/create'),
            'view' => ViewMovieSession::route('/{record}'),
            'edit' => EditMovieSession::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Sesiones';
    }

    public static function getModelLabel(): string
    {
        return 'sesión';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Sesiones';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Gestión del cine';
    }
}
