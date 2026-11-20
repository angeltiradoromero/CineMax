<?php

namespace App\Filament\Resources\MovieSessions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MovieSessionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('movie.title')
                    ->label('Película')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('room.name')
                    ->label('Sala')
                    ->formatStateUsing(function ($state) {
                        return "Sala {$state}";
                    })
                    ->sortable(),

                TextColumn::make('date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('start_time')
                    ->label('Hora')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Precio')
                    ->money('EUR')
                    ->sortable(),
            ])
            ->defaultSort('date', 'asc')

            ->filters([
                SelectFilter::make('movie_id')
                    ->label('Filtrar por película')
                    ->relationship('movie', 'title')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('room_id')
                    ->label('Filtrar por sala')
                    ->relationship('room', 'name'),
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
