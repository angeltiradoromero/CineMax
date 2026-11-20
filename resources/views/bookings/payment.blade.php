<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pago - Cinemax</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#09090b] text-white min-h-screen flex flex-col">

    @include('layouts.navigation')

    <main class="max-w-4xl mx-auto px-6 py-12 flex-grow w-full">

        <div class="text-center mb-12">

            <span class="text-red-500 uppercase tracking-[0.3em] text-xs font-bold">
                Último paso
            </span>

            <h1 class="text-4xl md:text-5xl font-black mt-3">
                PASARELA DE <span class="text-red-600">PAGO</span>
            </h1>

            <p class="text-gray-400 mt-4">
                Confirma tu compra para recibir tus entradas.
            </p>

        </div>

        @php
            $firstBooking = $bookings->first();
        @endphp

        <div class="bg-[#151518] border border-white/10 rounded-2xl p-6 md:p-8">

            <h2 class="text-xl font-bold mb-6">
                Resumen de compra
            </h2>

            <div class="mb-8">

                <h3 class="font-bold text-lg">
                    {{ $firstBooking->movieSession->movie->title }}
                </h3>

                <p class="text-gray-400 text-sm">
                    {{ $firstBooking->movieSession->room->name ?? 'Sala de cine' }}
                </p>

                <p class="text-gray-500 text-xs mt-1">
                    {{ $bookings->count() }} entradas seleccionadas
                </p>

            </div>

            <div class="border-y border-white/10 py-5 space-y-3">

                @foreach ($bookings as $booking)
                    <div class="flex justify-between text-sm">

                        <span class="text-gray-400">
                            Butaca {{ $booking->seat->row }}{{ $booking->seat->number }}
                        </span>

                        <span>
                            {{ number_format((float) $booking->price, 2, ',', '.') }} €
                        </span>

                    </div>
                @endforeach

            </div>

            <div class="flex justify-between items-center py-6">

                <span class="text-gray-300 font-semibold">
                    Total a pagar
                </span>

                <span class="text-3xl font-black text-red-600">
                    {{ number_format((float) $total, 2, ',', '.') }} €
                </span>

            </div>

            <div class="bg-[#09090b] border border-red-600/30 rounded-xl p-5 mb-8">

                <div class="flex items-center gap-3 mb-4">

                    <div class="w-12 h-12 bg-red-600/10 rounded-xl flex items-center justify-center text-2xl">
                        💳
                    </div>
                </div>

            </div>

            <form action="{{ route('bookings.confirm-payment', $bookingGroup) }}" method="POST">

                @csrf

                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl transition shadow-lg shadow-red-600/20">

                    Confirmar pago y obtener entradas

                </button>

            </form>

            <a href="{{ route('bookings.cart', $bookingGroup) }}"
                class="block text-center text-gray-400 hover:text-white mt-5 text-sm">

                Volver al carrito

            </a>

        </div>

    </main>

    @include('layouts.footer')

</body>

</html>
