<?php

namespace App\Filament\Resources\Rooms\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la sala')
                    ->description('Configura los datos principales de la sala de cine.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Número de sala')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(1)
                            ->placeholder('1'),

                        TextInput::make('capacity')
                            ->label('Capacidad')
                            ->numeric()
                            ->required()
                            ->minValue(50)
                            ->maxValue(200)
                            ->default(50)
                            ->integer()
                            ->live()
                            ->suffix('asientos'),

                        Toggle::make('is_active')
                            ->label('Sala activa')
                            ->default(true)
                            ->required(),

                        Repeater::make('rows')
                            ->label('Distribución por filas')
                            ->addActionLabel('Añadir fila')
                            ->dehydrated(false)
                            ->live()
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('seats')
                                    ->label('Cantidad de asientos en esta fila')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->maxValue(30)
                                    ->default(10)
                                    ->required(),
                            ])

                            ->addable(function (Get $get) {
                                $capacity = (int) $get('capacity');
                                $rows = $get('rows');
                                $total = 0;

                                if ($rows) {
                                    foreach ($rows as $row) {
                                        if (isset($row['seats'])) {
                                            $total = $total + (int) $row['seats'];
                                        }
                                    }
                                }

                                if ($total >= $capacity) {
                                    return false;
                                }

                                return true;
                            })

                            ->rules([
                                function (Get $get) {
                                    $capacity = (int) $get('capacity');
                                    $rows = $get('rows');
                                    $total = 0;

                                    if ($rows) {
                                        foreach ($rows as $row) {
                                            if (isset($row['seats'])) {
                                                $total = $total + (int) $row['seats'];
                                            }
                                        }
                                    }

                                    if ($total > $capacity) {
                                        return 'prohibited';
                                    }

                                    return 'nullable';
                                }

                            ])

                            ->validationMessages([
                                'prohibited' => 'La suma de asientos supera la capacidad de la sala.'
                            ])
                    ])


                    ->columns(2),
            ]);
    }
}
