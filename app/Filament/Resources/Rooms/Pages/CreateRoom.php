<?php

namespace App\Filament\Resources\Rooms\Pages;

use App\Filament\Resources\Rooms\RoomResource;
use App\Models\Cinema\Seat;
use Filament\Resources\Pages\CreateRecord;

class CreateRoom extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    protected function afterCreate(): void
    {
        $rows = $this->data['rows'];
        $rowNumber = 1;

        if ($rows) {
            foreach ($rows as $row) {
                $seatsCount = 0;

                if (isset($row['seats'])) {
                    $seatsCount = (int)$row['seats'];
                }

                for ($i = 1; $i <= $seatsCount; $i++) {
                    Seat::create([
                        'room_id' => $this->record->id,
                        'row' => $rowNumber,
                        'number' => $i
                    ]);
                }

                $rowNumber++;
            }
        }
    }
}
