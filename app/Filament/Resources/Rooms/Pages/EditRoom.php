<?php

namespace App\Filament\Resources\Rooms\Pages;

use App\Filament\Resources\Rooms\RoomResource;
use App\Models\Cinema\Seat;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRoom extends EditRecord
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $room): array
    {
        $seats = Seat::where('room_id', $this->record->id)->get();
        $seatsRow = [];

        // Contamos cuántos asientos hay en cada fila (Fila 1, Fila 2...)
        foreach ($seats as $seat) {
            $rowNumber = $seat->row;

            if (!isset($seatsRow[$rowNumber])) {
                $seatsRow[$rowNumber] = 0;
            }

            $seatsRow[$rowNumber]++;
        }

        // Preparamos la lista con el formato que entiende el Repeater ('seats')
        $rowsForm = [];
        foreach ($seatsRow as $seats) {
            $rowsForm[] = [
                'seats' => $seats,
            ];
        }

        $room['rows'] = $rowsForm;

        return $room;
    }

    protected function afterSave(): void
    {
        // 1. Borramos los asientos anteriores de esta sala
        Seat::where('room_id', $this->record->id)->delete();

        $rows = [];

        if (isset($this->data['rows'])) {
            $rows = $this->data['rows'];
        }

        $rowNumber = 1;

        // 2. Creamos los nuevos asientos con las filas actualizadas
        if ($rows) {
            foreach ($rows as $row) {
                $seatsCount = 0;

                if (isset($row['seats'])) {
                    $seatsCount = (int) $row['seats'];
                }

                for ($i = 1; $i <= $seatsCount; $i++) {
                    Seat::create([
                        'room_id' => $this->record->id,
                        'row' => $rowNumber,
                        'number' => $i,
                    ]);
                }

                $rowNumber++;
            }
        }
    }
}
