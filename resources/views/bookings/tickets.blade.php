<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Mis entradas - {{ config('app.name', 'CineMax') }}</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-[#09090b] text-white font-sans antialiased min-h-screen">

    @include('layouts.navigation')

    <!-- CABECERA -->
    <header class="bg-[#111113] border-b border-white/10">

        <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">

            <a href="{{ route('movies.now-playing') }}"
                class="text-sm font-semibold text-gray-300 hover:text-red-500 transition">
                ← Volver a cartelera
            </a>

        </div>

    </header>


    <!-- CONTENIDO PRINCIPAL -->
    <main class="max-w-5xl mx-auto px-6 py-12">

        <!-- TITULO -->
        <div class="mb-10">

            <span class="text-red-500 uppercase tracking-[0.3em] text-xs font-bold">
                Mi cuenta
            </span>

            <h1 class="text-4xl md:text-5xl font-black mt-3">
                MIS <span class="text-red-600">ENTRADAS</span>
            </h1>

            <p class="text-gray-400 mt-4 text-lg">
                Aquí puedes consultar todas tus entradas de cine.
            </p>

            <div class="w-20 h-1 bg-red-600 mt-6 rounded-full"></div>

        </div>


        <!-- MENSAJE DE EXITO -->
        @if (session('success'))
            <div
                class="mb-8 p-5 bg-emerald-900/20 border border-emerald-500/30 text-emerald-400 rounded-xl flex items-center gap-3">

                <span class="text-2xl">✓</span>

                <span class="font-medium">
                    {{ session('success') }}
                </span>

            </div>
        @endif


        <!-- SI NO HAY ENTRADAS -->
        @if ($tickets->isEmpty())

            <div class="bg-[#151518] border border-white/10 rounded-2xl p-12 text-center shadow-xl">

                <div class="text-7xl mb-6">
                    🎟️
                </div>

                <h2 class="text-2xl font-black mb-3">
                    Todavía no tienes entradas
                </h2>

                <p class="text-gray-400 mb-8">
                    Cuando compres tus entradas, aparecerán aquí.
                </p>

                <a href="{{ route('movies.now-playing') }}"
                    class="inline-block px-8 py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition shadow-lg shadow-red-900/20">
                    EXPLORAR CARTELERA →
                </a>

            </div>
        @else
            <!-- RESUMEN -->
            <div class="bg-[#151518] border border-white/10 rounded-xl p-6 mb-8 flex items-center justify-between">

                <div>
                    <p class="text-gray-400 text-sm">
                        Entradas adquiridas
                    </p>

                    <p class="text-3xl font-black text-white mt-1">
                        {{ $tickets->count() }}
                    </p>
                </div>

                <div class="w-14 h-14 bg-red-600/10 rounded-xl flex items-center justify-center text-3xl">
                    🎟️
                </div>

            </div>


            <!-- LISTADO DE ENTRADAS -->
            <div class="space-y-6">

                @foreach ($tickets as $ticket)
                    @php
                        $session = $ticket->booking->movieSession;
                        $seat = $ticket->booking->seat;
                    @endphp

                    <!-- TARJETA DE ENTRADA -->
                    <div
                        class="bg-[#151518] border border-white/10 rounded-2xl overflow-hidden shadow-xl hover:border-red-600/40 transition duration-300">

                        <div class="flex flex-col md:flex-row">

                            <!-- INFORMACION PELICULA -->
                            <div class="flex-1 p-6 md:p-8">

                                <div class="flex flex-wrap items-center gap-3 mb-5">

                                    <span
                                        class="text-xs font-black uppercase tracking-wider text-red-400 bg-red-600/10 border border-red-600/20 px-3 py-2 rounded-lg">
                                        {{ $ticket->code }}
                                    </span>

                                    <span
                                        class="text-xs text-emerald-400 bg-emerald-500/10 px-3 py-2 rounded-lg font-bold">
                                        ✓ Entrada comprada
                                    </span>

                                </div>

                                <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-2">
                                    Película
                                </p>

                                <h2 class="text-2xl md:text-3xl font-black text-white">
                                    {{ $session->movie->title }}
                                </h2>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-7">

                                    <!-- FECHA -->
                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-11 h-11 bg-red-600/10 rounded-lg flex items-center justify-center text-xl">
                                            📅
                                        </div>

                                        <div>
                                            <p class="text-xs text-gray-500 uppercase font-bold">
                                                Fecha
                                            </p>

                                            <p class="text-white font-semibold mt-1">
                                                {{ \Illuminate\Support\Carbon::parse($session->date)->format('d/m/Y') }}
                                            </p>
                                        </div>

                                    </div>


                                    <!-- HORA -->
                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-11 h-11 bg-red-600/10 rounded-lg flex items-center justify-center text-xl">
                                            🕒
                                        </div>

                                        <div>
                                            <p class="text-xs text-gray-500 uppercase font-bold">
                                                Hora
                                            </p>

                                            <p class="text-white font-semibold mt-1">
                                                {{ \Illuminate\Support\Carbon::parse($session->start_time)->format('H:i') }}
                                            </p>
                                        </div>

                                    </div>


                                    <!-- SALA -->
                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-11 h-11 bg-red-600/10 rounded-lg flex items-center justify-center text-xl">
                                            🎬
                                        </div>

                                        <div>
                                            <p class="text-xs text-gray-500 uppercase font-bold">
                                                Sala
                                            </p>

                                            <p class="text-white font-semibold mt-1">
                                                {{ $session->room->name }}
                                            </p>
                                        </div>

                                    </div>


                                    <!-- BUTACA -->
                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-11 h-11 bg-red-600/10 rounded-lg flex items-center justify-center text-xl">
                                            💺
                                        </div>

                                        <div>
                                            <p class="text-xs text-gray-500 uppercase font-bold">
                                                Asiento
                                            </p>

                                            <p class="text-white font-semibold mt-1">
                                                Fila {{ $seat->row }} · Butaca {{ $seat->number }}
                                            </p>
                                        </div>

                                    </div>

                                </div>

                            </div>


                            <!-- SEPARADOR -->
                            <div class="hidden md:flex items-center">
                                <div class="h-4/5 border-l-2 border-dashed border-white/10"></div>
                            </div>


                            <!-- PRECIO -->
                            <div
                                class="md:w-52 bg-[#111113] border-t md:border-t-0 border-white/10 p-6 flex flex-col justify-center items-center text-center">

                                <span class="text-gray-500 text-xs uppercase font-bold tracking-widest">
                                    Total entrada
                                </span>

                                <p class="text-3xl font-black text-red-500 mt-3">
                                    {{ number_format($ticket->booking->price, 2) }} €
                                </p>

                                <div class="w-full h-px bg-white/10 my-5"></div>

                                <p class="text-xs text-gray-500">
                                    Código de entrada
                                </p>

                                <p class="text-white text-sm font-bold mt-2 break-all">
                                    {{ $ticket->code }}
                                </p>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        @endif

    </main>


    @include('layouts.footer')
</body>

</html>
