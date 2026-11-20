<?php

namespace App\Http\Controllers\Cinema;

use App\Http\Controllers\Controller;
use App\Models\Cinema\MovieSession;
use App\Services\Cinema\BookingService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /**
     * Mostrar las butacas disponibles de una sesión.
     */
    public function showSeats($sessionId, BookingService $bookingService)
    {
        $session = MovieSession::with(['movie', 'room'])
            ->findOrFail($sessionId);

        $seatsData = $bookingService->getSessionSeats($session);

        $seatsByRow = $seatsData['seatsByRow'];
        $occupiedSeats = $seatsData['occupiedSeats'];

        return view('bookings.seats', compact(
            'session',
            'seatsByRow',
            'occupiedSeats'
        ));
    }

    /**
     * Guardar las butacas seleccionadas y crear una reserva temporal.
     */
    public function store(
        Request $request,
        $sessionId,
        BookingService $bookingService
    ) {
        $request->validate([
            'seats' => ['required', 'array', 'min:1'],
            'seats.*' => ['required', 'integer', 'distinct', 'exists:seats,id'],
        ]);

        $session = MovieSession::findOrFail($sessionId);

        $bookingGroup = $bookingService->createPendingBooking(
            $request->user()->id,
            $session,
            $request->input('seats')
        );

        return redirect()
            ->route('bookings.cart', $bookingGroup)
            ->with('success', 'Tus asientos están reservados durante 15 minutos.');
    }

    /**
     * Mostrar el carrito con las reservas pendientes.
     */
    public function cart(
        Request $request,
        string $bookingGroup,
        BookingService $bookingService
    ) {
        $bookings = $bookingService->getPendingCart(
            $request->user()->id,
            $bookingGroup
        );

        if ($bookings->isEmpty()) {
            return redirect()
                ->route('movies.now-playing')
                ->with('error', 'El carrito está vacío o tu reserva ha caducado.');
        }

        $total = $bookings->sum('price');

        return view('bookings.cart', compact(
            'bookings',
            'bookingGroup',
            'total'
        ));
    }

    /**
     * Mostrar la pantalla donde se realiza el pago.
     */
    public function payment(
        Request $request,
        string $bookingGroup,
        BookingService $bookingService
    ) {
        $bookings = $bookingService->getPendingCart(
            $request->user()->id,
            $bookingGroup
        );

        if ($bookings->isEmpty()) {
            return redirect()
                ->route('movies.now-playing')
                ->with('error', 'Tu reserva ha caducado.');
        }

        $total = $bookings->sum('price');

        return view('bookings.payment', compact(
            'bookings',
            'bookingGroup',
            'total'
        ));
    }

    /**
     * Confirmar el pago y generar las entradas.
     */
    public function confirmPayment(
        Request $request,
        string $bookingGroup,
        BookingService $bookingService
    ) {
        try {
            $bookingService->confirmPayment(
                $request->user()->id,
                $bookingGroup
            );

            return redirect()
                ->route('bookings.tickets')
                ->with('success', '¡Compra realizada correctamente! Tus entradas están disponibles.');
        } catch (ValidationException $e) {
            return redirect()
                ->route('movies.now-playing')
                ->withErrors($e->errors());
        }
    }

    /**
     * Mostrar todas las entradas compradas por el usuario.
     */
    public function myTickets(
        Request $request,
        BookingService $bookingService
    ) {
        $tickets = $bookingService->getUserTickets(
            $request->user()->id
        );

        return view('bookings.tickets', compact('tickets'));
    }

    /**
     * Mostrar todas las reservas pendientes del usuario.
     */
    public function myCart(
        Request $request,
        BookingService $bookingService
    ) {
        $cart = $bookingService->getUserCart(
            $request->user()->id
        );

        return view('bookings.cart', [
            'bookings' => $cart['bookings'],
            'total' => $cart['total'],
        ]);
    }
}
