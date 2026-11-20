<?php

namespace App\Filament\Resources\Movies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MovieForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tmdb_id')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('overview')
                    ->columnSpanFull(),
                TextInput::make('runtime')
                    ->numeric(),
                TextInput::make('genre'),
                TextInput::make('certification'),
                TextInput::make('director'),
                TextInput::make('poster_path'),
                TextInput::make('backdrop_path'),
                DatePicker::make('release_date'),
                TextInput::make('tmdb_status'),
                Select::make('status')
                    ->options(['now_playing' => 'Now playing', 'upcoming' => 'Upcoming', 'inactive' => 'Inactive'])
                    ->required(),
            ]);
    }
}
