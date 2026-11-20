<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Mi carrito - Cinemax</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#09090b] text-white min-h-screen flex flex-col">

    @include('layouts.navigation')

    <main class="max-w-5xl mx-auto px-6 py-12 flex-grow w-full">

        <div class="text-center mb-12">
            <span class="text-red-500 uppercase tracking-[0.3em] text-xs font-bold">
                Tu compra
            </span>

            <h1 class="text-4xl md:text-5xl font-black mt-3">
                MI <span class="text-red-600">CARRITO</span>
            </h1>

            <p class="text-gray-400 mt-4">
                Revisa tus reservas antes de continuar al pago.
            </p>
        </div>

        @if ($bookings->isEmpty())

            <div class="bg-[#151518] border border-white/10 rounded-2xl p-12 text-center">
                <div class="text-6xl mb-5">🛒</div>

                <h2 class="text-2xl font-bold mb-3">
                    Tu carrito está vacío
                </h2>

                <p class="text-gray-400 mb-8">
                    Todavía no tienes reservas pendientes.
                </p>

                <a href="{{ route('movies.now-playing') }}"
                    class="inline-block bg-red-600 hover:bg-red-700 px-8 py-3 rounded-xl font-bold transition">
                    Ver cartelera
                </a>
            </div>
        @else
            @foreach ($bookings->groupBy('booking_group') as $bookingGroup => $groupBookings)
                @php
                    $firstBooking = $groupBookings->first();
                    $groupTotal = $groupBookings->sum('price');
                @endphp

                <div class="bg-[#151518] border border-white/10 rounded-2xl p-6 md:p-8 mb-8">

                    <div class="mb-8">
                        <span class="text-red-500 text-xs font-bold uppercase tracking-widest">
                            Reserva pendiente
                        </span>

                        <h2 class="text-2xl font-bold mt-2">
                            {{ $firstBooking->movieSession->movie->title }}
                        </h2>

                        <p class="text-gray-400 text-sm mt-2">
                            Sala: {{ $firstBooking->movieSession->room->name ?? 'Sala' }}
                        </p>
                    </div>

                    <div class="space-y-4">

                        @foreach ($groupBookings as $booking)
                            <div class="flex justify-between items-center border-b border-white/10 pb-4">

                                <div>
                                    <p class="font-semibold">
                                        Butaca {{ $booking->seat->row }}{{ $booking->seat->number }}
                                    </p>

                                    <p class="text-gray-500 text-xs mt-1">
                                        Reserva pendiente
                                    </p>
                                </div>

                                <p class="font-bold">
                                    {{ number_format((float) $booking->price, 2, ',', '.') }} €
                                </p>

                            </div>
                        @endforeach

                    </div>

                    <div class="mt-8 border-t border-white/10 pt-6">

                        <div class="flex justify-between items-center mb-6">

                            <span class="text-gray-400">
                                Total de esta reserva
                            </span>

                            <span class="text-3xl font-black text-red-600">
                                {{ number_format((float) $groupTotal, 2, ',', '.') }} €
                            </span>

                        </div>

                        <a href="{{ route('bookings.payment', $bookingGroup) }}"
                            class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-red-600/20">
                            Proceder al pago →
                        </a>

                    </div>

                </div>
            @endforeach

        @endif

        <div class="text-center mt-8">
            <a href="{{ route('movies.now-playing') }}" class="text-gray-400 hover:text-white transition text-sm">
                ← Seguir explorando películas
            </a>
        </div>

        <p class="text-center text-gray-500 text-xs mt-8">
            Tus asientos están reservados durante 15 minutos.
        </p>

    </main>

    @include('layouts.footer')

</body>

</html>
