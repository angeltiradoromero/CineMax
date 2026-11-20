<?php

namespace App\Services\Cinema;

use App\Models\Cinema\Booking;
use App\Models\Cinema\MovieSession;
use App\Models\Cinema\Seat;
use App\Models\Cinema\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Obtener las butacas de una sesión y comprobar cuáles están ocupadas.
     */
    public function getSessionSeats(MovieSession $session): array
    {
        $seats = Seat::where('room_id', $session->room_id)
            ->orderBy('row')
            ->orderBy('number')
            ->get();

        $seatsByRow = [];

        foreach ($seats as $seat) {
            $seatsByRow[$seat->row][] = $seat;
        }

        $occupiedSeats = Booking::where('movie_session_id', $session->id)
            ->where(function ($query) {
                $query->where('status', 'confirmed')
                    ->orWhere(function ($query) {
                        $query->where('status', 'pending')
                            ->where('expires_at', '>', now());
                    });
            })
            ->pluck('seat_id')
            ->toArray();

        return [
            'seatsByRow' => $seatsByRow,
            'occupiedSeats' => $occupiedSeats,

        ];
    }

    /**
     * Crear una reserva temporal de las butacas seleccionadas.
     * Las entradas todavía no se generan.
     */
    public function createPendingBooking(
        int $userId,
        MovieSession $session,
        array $seatIds
    ): string {
        return DB::transaction(function () use ($userId, $session, $seatIds) {
            $seatIds = array_unique($seatIds);

            $seats = Seat::whereIn('id', $seatIds)
                ->where('room_id', $session->room_id)
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $hasOccupiedSeats = Booking::where('movie_session_id', $session->id)
                ->whereIn('seat_id', $seatIds)
                ->where(function ($query) {
                    $query->where('status', 'confirmed')
                        ->orWhere(function ($query) {
                            $query->where('status', 'pending')
                                ->where('expires_at', '>', now());
                        });
                })
                ->exists();

            if ($hasOccupiedSeats) {
                throw ValidationException::withMessages([
                    'seats' => 'Alguna de las butacas ya está ocupada.',
                ]);
            }

            $bookingGroup = (string) Str::uuid();

            foreach ($seatIds as $seatId) {
                Booking::create([
                    'user_id' => $userId,
                    'movie_session_id' => $session->id,
                    'seat_id' => $seatId,
                    'booking_group' => $bookingGroup,
                    'price' => $session->price,
                    'status' => 'pending',
                    'expires_at' => now()->addMinutes(1),
                ]);
            }

            return $bookingGroup;
        });
    }

    /**
     * Obtener las reservas pendientes de un grupo.
     */
    public function getPendingCart(int $userId, string $bookingGroup)
    {
        return Booking::where('user_id', $userId)
            ->where('booking_group', $bookingGroup)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->with([
                'movieSession.movie',
                'movieSession.room',
                'seat',
            ])
            ->get();
    }

    /**
     * Confirmar el pago y generar las entradas.
     */
    public function confirmPayment(
        int $userId,
        string $bookingGroup
    ): void {

        DB::transaction(function () use ($userId, $bookingGroup) {

            $bookings = Booking::where('user_id', $userId)
                ->where('booking_group', $bookingGroup)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->get();

            if ($bookings->isEmpty()) {
                throw ValidationException::withMessages([
                    'payment' => 'No existe una reserva pendiente.',
                ]);
            }

            foreach ($bookings as $booking) {

                if (!$booking->expires_at || $booking->expires_at->isPast()) {
                    throw ValidationException::withMessages([
                        'payment' => 'Tu reserva ha caducado. Selecciona nuevamente tus asientos.',
                    ]);
                }

                $booking->update([
                    'status' => 'confirmed',
                    'expires_at' => null,
                ]);

                $booking->ticket()->create([
                    'code' => 'TKT-' . Str::upper(Str::random(8)),
                    'status' => 'valid',
                    'purchased_at' => now(),
                ]);
            }
        });
    }

    /**
     * Obtener todas las entradas de un usuario.
     */
    public function getUserTickets(int $userId)
    {
        return Ticket::whereHas('booking', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('status', 'confirmed');
        })
            ->with([
                'booking.movieSession.movie',
                'booking.movieSession.room',
                'booking.seat',
            ])
            ->orderByDesc('purchased_at')
            ->get();
    }

    /**
     * Obtener el carrito del usuario con el precio total.
     */
    public function getUserCart(int $userId)
    {
        $bookings = Booking::with([
            'movieSession.movie',
            'movieSession.room',
            'seat',
        ])
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->orderByDesc('created_at')
            ->get();

        return [
            'bookings' => $bookings,
            'total' => $bookings->sum('price'),
        ];
    }
}
