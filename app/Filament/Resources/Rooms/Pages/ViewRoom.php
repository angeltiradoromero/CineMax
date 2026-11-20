<?php

namespace App\Filament\Resources\Rooms\Pages;

use App\Filament\Resources\Rooms\RoomResource;
use App\Models\Cinema\Seat;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRoom extends ViewRecord
{
    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
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
}
