<?php

namespace App\Filament\Resources\Movies\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MovieInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tmdb_id')
                    ->numeric(),
                TextEntry::make('title'),
                TextEntry::make('overview')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('runtime')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('genre')
                    ->placeholder('-'),
                TextEntry::make('certification')
                    ->placeholder('-'),
                TextEntry::make('director')
                    ->placeholder('-'),
                TextEntry::make('poster_path')
                    ->placeholder('-'),
                TextEntry::make('backdrop_path')
                    ->placeholder('-'),
                TextEntry::make('release_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('tmdb_status')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
