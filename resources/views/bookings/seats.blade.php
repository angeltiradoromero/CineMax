<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selección de butacas - {{ $session->movie->title }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-gray-50 text-gray-900 font-sans antialiased">

    @include('layouts.navigation')

    <main class="max-w-6xl mx-auto px-4 py-10">
        <!-- Datos de la película y la sesión -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900">{{ $session->movie->title }}</h1>
            <p class="text-gray-600 mt-1 text-sm">
                Sala {{ $session->room->name }} | 
                {{ date('d/m/Y', strtotime($session->date)) }} a las {{ substr($session->start_time, 0, 5) }}h | 
                <span class="font-semibold text-red-600">{{ number_format($session->price, 2, ',', '.') }} €</span>
            </p>
        </div>

        <!-- Si falla algo al reservar (ej. no elegir asiento), muestra el aviso -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg text-center font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('sessions.book', $session->id) }}" method="POST">
            @csrf

            <!-- Ordenamos las filas al revés para que la fila 1 quede abajo, cerca de la pantalla -->
            @php
                krsort($seatsByRow);
            @endphp

            <!-- Mapa de butacas -->
            <div class="space-y-2.5 mb-8 overflow-x-auto pb-2 touch-pan-x">
                @foreach ($seatsByRow as $rowNumber => $seats)
                    <div class="flex items-center w-fit mx-auto gap-1 sm:gap-1.5">
                        <!-- Número de fila (en móvil sale abreviado como F1, F2...) -->
                        <span class="w-8 sm:w-14 shrink-0 text-right text-xs sm:text-sm font-bold text-gray-500 mr-1.5 sm:mr-2">
                            <span class="sm:hidden">F{{ $rowNumber }}</span>
                            <span class="hidden sm:inline">Fila {{ $rowNumber }}</span>
                        </span>

                        @foreach ($seats as $seat)
                            <!-- Comprobamos si el ID de esta butaca ya está en la lista de ocupadas -->
                            @php
                                $isOccupied = in_array($seat->id, $occupiedSeats);
                            @endphp

                            @if ($isOccupied)
                                <!-- Asiento ocupado (no se puede pulsar) -->
                                <div class="w-6 h-6 sm:w-7 sm:h-7 shrink-0 rounded-t-md bg-red-500 text-white flex items-center justify-center text-[11px] font-bold cursor-not-allowed opacity-75"
                                    title="Asiento ocupado">
                                    {{ $seat->number }}
                                </div>
                            @else
                                <!-- Asiento libre: escondemos el checkbox real y cambiamos el color del cuadrado al marcarlo -->
                                <label class="cursor-pointer shrink-0">
                                    <input type="checkbox" name="seats[]" value="{{ $seat->id }}"
                                        class="seat-checkbox hidden peer">
                                    <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-t-md bg-emerald-500 hover:bg-emerald-600 peer-checked:bg-amber-500 text-white flex items-center justify-center text-[11px] font-bold transition shadow-sm">
                                        {{ $seat->number }}
                                    </div>
                                </label>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>

            <!-- Dibujo de la pantalla del cine -->
            <div class="w-3/4 mx-auto mb-10 text-center">
                <span class="text-xs uppercase tracking-widest text-gray-500 font-semibold block mb-2">Pantalla</span>
                <div class="h-3 bg-gray-400 rounded-b-full shadow-md"></div>
            </div>

            <!-- Leyenda de colores -->
            <div class="flex justify-center gap-6 text-sm text-gray-600 mb-8">
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded bg-emerald-500 inline-block"></span> Libre
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded bg-amber-500 inline-block"></span> Seleccionada
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded bg-red-500 inline-block"></span> Ocupada
                </div>
            </div>

            <!-- Caja resumen con el total y el botón de compra -->
            <div class="max-w-3xl mx-auto bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500">Butacas seleccionadas: <span id="selected-count" class="font-bold text-gray-900">0</span></p>
                    <p class="text-xl font-bold text-gray-900">Total a pagar: <span id="total-price" class="text-red-600">0,00</span> €</p>
                </div>

                <!-- Solo deja comprar si tiene la sesión iniciada -->
                @auth
                    <button type="submit"
                        class="w-full sm:w-auto px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition shadow">
                        Confirmar compra
                    </button>
                @else
                    <a href="{{ route('login') }}"
                        class="w-full sm:w-auto px-8 py-3 bg-gray-800 hover:bg-gray-900 text-white font-bold rounded-lg transition text-center">
                        Inicia sesión para comprar
                    </a>
                @endauth
            </div>
        </form>
    </main>

    <!-- Actualiza el contador y el precio total en tiempo real al marcar butacas -->
    <script>
        const checkboxes = document.querySelectorAll('.seat-checkbox');
        const countEl = document.getElementById('selected-count');
        const totalEl = document.getElementById('total-price');
        const pricePerSeat = {{ $session->price }};

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                const selected = document.querySelectorAll('.seat-checkbox:checked').length;
                countEl.textContent = selected;
                totalEl.textContent = (selected * pricePerSeat).toFixed(2).replace('.', ',');
            });
        });
    </script>
</body>

</html>
