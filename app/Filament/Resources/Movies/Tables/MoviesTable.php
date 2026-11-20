<?php

namespace App\Filament\Resources\Movies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MoviesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tmdb_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('runtime')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('genre')
                    ->searchable(),
                TextColumn::make('certification')
                    ->searchable(),
                TextColumn::make('director')
                    ->searchable(),
                TextColumn::make('poster_path')
                    ->searchable(),
                TextColumn::make('backdrop_path')
                    ->searchable(),
                TextColumn::make('release_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('tmdb_status')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
