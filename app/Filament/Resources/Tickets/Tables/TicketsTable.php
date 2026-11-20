<?php

namespace App\Filament\Resources\Tickets\Tables;

use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('booking.user.name')
                    ->label('Cliente')
                    ->searchable(),

                TextColumn::make('booking.movieSession.movie.title')
                    ->label('Película')
                    ->searchable(),

                TextColumn::make('booking.movieSession.date')
                    ->label('Sesión')
                    ->formatStateUsing(function ($record) {
                        $session = $record->booking->movieSession;
                        $date = Carbon::parse($session->date)->format('d/m/Y');
                        $time = Carbon::parse($session->start_time)->format('H:i');

                        return "{$date} · {$time}";
                    })
                    ->sortable(),

                TextColumn::make('booking.seat')
                    ->label('Butaca')
                    ->formatStateUsing(function ($record) {
                        $room = $record->booking->movieSession->room->name;
                        $row = $record->booking->seat->row;
                        $number = $record->booking->seat->number;

                        return "Sala {$room} · Fila {$row}, Asiento {$number}";
                    }),

                TextColumn::make('booking.price')
                    ->label('Precio')
                    ->money('EUR'),

                TextColumn::make('purchased_at')
                    ->label('Comprada el')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('purchased_at', 'desc')
            ->recordActions([
                DeleteAction::make()
                    ->label('Anular entrada')
                    ->modalHeading('¿Anular esta entrada?')
                    ->modalDescription('Se cancelará la entrada y la butaca volverá a quedar libre para otros clientes.')
                    ->visible(function ($record) {
                        $session = $record->booking->movieSession;
                        $dateString = Carbon::parse($session->date)->toDateString();
                        $sessionDateTime = Carbon::parse($dateString . ' ' . $session->start_time);

                        return $sessionDateTime->isFuture();
                    })
                    ->using(function ($record) {
                        $booking = $record->booking;
                        $record->delete();

                        if ($booking) {
                            $booking->delete();
                        }
                    }),
            ]);
    }
}
