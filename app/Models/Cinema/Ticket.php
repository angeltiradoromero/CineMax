<?php

namespace App\Models\Cinema;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['booking_id', 'code', 'status', 'purchased_at',])]

class Ticket extends Model
{
    protected function casts(): array
    {
        return [
            'purchased_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
