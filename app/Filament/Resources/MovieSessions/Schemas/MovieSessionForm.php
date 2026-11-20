<?php

namespace App\Filament\Resources\MovieSessions\Schemas;

use App\Models\Cinema\MovieSession;
use App\Models\Movies\Movie;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class MovieSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Programación de la sesión')
                    ->description('Asigna una película, una sala activa, el horario y el precio de la entrada.')
                    ->schema([
                        Select::make('movie_id')
                            ->label('Película')
                            ->relationship('movie', 'title')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),

                        Select::make('room_id')
                            ->label('Sala')
                            ->relationship(
                                name: 'room',
                                titleAttribute: 'name',
                                modifyQueryUsing: function (Builder $query) {
                                    return $query->where('is_active', true);
                                }
                            )
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                return "Sala {$record->name} ({$record->capacity} asientos)";
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),

                        DatePicker::make('date')
                            ->label('Fecha')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->minDate(now()->startOfDay())
                            ->live()
                            ->required(),

                        Select::make('start_time')
                            ->label('Hora de inicio')
                            ->native(false)
                            ->options([
                                '16:00' => '16:00',
                                '16:30' => '16:30',
                                '18:00' => '18:00',
                                '18:30' => '18:30',
                                '20:15' => '20:15',
                                '20:45' => '20:45',
                                '22:30' => '22:30',
                                '23:00' => '23:00',
                            ])
                            ->disableOptionWhen(function (string $value, Get $get, $record) {
                                $roomId = $get('room_id');
                                $date = $get('date');
                                $movieId = $get('movie_id');

                                if (!$roomId || !$date || !$movieId) {
                                    return false;
                                }

                                $movie = Movie::find($movieId);
                                $dateString = Carbon::parse($date)->toDateString();
                                $start = Carbon::parse($dateString . ' ' . $value);
                                $end = $start->copy()->addMinutes((int) $movie->runtime + 15);

                                $sessions = MovieSession::where('room_id', $roomId)
                                    ->whereDate('date', $dateString)
                                    ->with('movie')
                                    ->get();

                                foreach ($sessions as $session) {
                                    if ($record && $session->id === $record->id) {
                                        continue;
                                    }

                                    $existingTime = Carbon::parse($session->start_time)->format('H:i');

                                    if ($value === $existingTime) {
                                        return true;
                                    }

                                    $sessionDateString = Carbon::parse($session->date)->toDateString();
                                    $sessionStart = Carbon::parse($sessionDateString . ' ' . $session->start_time);
                                    $sessionEnd = $sessionStart->copy()->addMinutes((int) $session->movie->runtime + 15);

                                    if ($start < $sessionEnd && $end > $sessionStart) {
                                        return true;
                                    }
                                }

                                return false;
                            })
                            ->required(),

                        TextInput::make('price')
                            ->label('Precio de entrada')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.50)
                            ->default(8.50)
                            ->suffix('€')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
