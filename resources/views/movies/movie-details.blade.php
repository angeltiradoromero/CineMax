<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $movieBd->title }} - {{ config('app.name', 'MiCartelera') }}</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-[#09090b] text-white font-sans antialiased min-h-screen flex flex-col">

    @include('layouts.navigation')

    <main class="max-w-7xl mx-auto px-6 py-12 flex-grow w-full">

        <!-- VOLVER A CARTELERA -->
        <div class="mb-8">
            <a href="{{ route('movies.now-playing') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-400 hover:text-red-500 transition">
                ← Volver a la cartelera
            </a>
        </div>

        <!-- FICHA DE LA PELÍCULA -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">

            <!-- COLUMNA IZQUIERDA: PÓSTER Y SESIONES -->
            <div class="lg:col-span-4 space-y-8">

                <!-- PÓSTER -->
                <div>
                    <img src="https://image.tmdb.org/t/p/w500{{ $movieBd->poster_path }}" alt="{{ $movieBd->title }}"
                        class="w-full h-auto aspect-[2/3] object-cover rounded-2xl shadow-xl border border-white/10">
                </div>

                <!-- SESIONES DISPONIBLES -->
                <div class="bg-[#151518] border border-white/10 rounded-2xl p-6 shadow-xl">

                    <div class="mb-6">
                        <h2 class="text-xl font-black flex items-center gap-2">
                            <span class="text-red-600">|</span> Sesiones disponibles
                        </h2>

                        <p class="text-gray-400 text-sm mt-1">
                            Selecciona un horario para elegir tus butacas
                        </p>
                    </div>

                    @if ($sessions->isEmpty())

                        <div class="bg-[#09090b] border border-white/5 rounded-xl p-5 text-center">
                            <p class="text-gray-400 text-sm">
                                No hay sesiones programadas para esta película en este momento.
                            </p>
                        </div>
                    @else
                        <div class="grid grid-cols-2 gap-3">

                            @foreach ($sessions as $session)
                                <a href="{{ route('sessions.seats', $session->id) }}"
                                    class="group bg-[#09090b] border border-white/10 hover:border-red-600 hover:bg-red-600/10 rounded-xl p-3 transition text-center shadow-md">

                                    <span class="block text-xs text-gray-400 group-hover:text-gray-300">
                                        {{ \Illuminate\Support\Carbon::parse($session->date)->format('d/m/Y') }}
                                    </span>

                                    <span
                                        class="block text-2xl font-black text-white group-hover:text-red-500 my-1 transition">
                                        {{ \Illuminate\Support\Carbon::parse($session->start_time)->format('H:i') }}
                                    </span>

                                    <span class="block text-xs font-medium text-gray-400">
                                        Sala {{ $session->room->name }}
                                    </span>

                                    <span
                                        class="inline-block mt-2 px-2.5 py-1 bg-red-600/20 text-red-400 text-xs font-bold rounded-md">
                                        {{ number_format($session->price, 2) }} €
                                    </span>

                                </a>
                            @endforeach

                        </div>

                    @endif

                </div>

            </div>

            <!-- COLUMNA DERECHA: INFORMACIÓN DE LA PELÍCULA -->
            <div class="lg:col-span-8 space-y-8">

                <!-- TÍTULO, FAVORITOS Y ESPECIFICACIONES -->
                <div class="bg-[#151518] border border-white/10 rounded-2xl p-6 md:p-8 shadow-xl">

                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">

                        <div>
                            <span
                                class="inline-block bg-red-600 text-white text-[11px] font-black uppercase tracking-wider px-3 py-1 rounded-md mb-3">
                                En cartelera
                            </span>

                            <h1 class="text-3xl md:text-5xl font-black tracking-tight text-white">
                                {{ $movieBd->title }}
                            </h1>
                        </div>

                        <!-- BOTÓN DE FAVORITOS -->
                        @auth
                            <form class="shrink-0">
                                <button type="button" data-url="{{ route('favorites.toggle', $movieBd->id) }}"
                                    class="favorite-btn inline-flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-sm transition cursor-pointer border
                                    {{ auth()->user()->favorites->contains($movieBd->id)
                                        ? 'bg-red-600 text-white border-red-600 hover:bg-red-700'
                                        : 'bg-[#09090b] text-gray-200 border-white/15 hover:border-red-600 hover:text-red-500' }}">

                                    @if (auth()->user()->favorites->contains($movieBd->id))
                                        ♥ Quitar de favoritos
                                    @else
                                        ♡ Añadir a favoritos
                                    @endif

                                </button>
                            </form>
                        @endauth

                    </div>

                    <!-- DATOS DE LA PELÍCULA -->
                    <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-white/10">

                        @if (!empty($movieBd->release_date))
                            <span
                                class="bg-[#09090b] border border-white/10 px-3 py-2 rounded-lg text-xs font-semibold text-gray-300">
                                📅 Estreno:
                                {{ \Illuminate\Support\Carbon::parse($movieBd->release_date)->format('d/m/Y') }}
                            </span>
                        @endif

                        @if (!empty($movieBd->runtime))
                            <span
                                class="bg-[#09090b] border border-white/10 px-3 py-2 rounded-lg text-xs font-semibold text-gray-300">
                                ⏱️ {{ $movieBd->runtime }} min
                            </span>
                        @endif

                        @if (!empty($movieBd->genre))
                            <span
                                class="bg-[#09090b] border border-white/10 px-3 py-2 rounded-lg text-xs font-semibold text-gray-300">
                                🎭
                                {{ is_array($movieBd->genre) ? implode(', ', $movieBd->genre) : $movieBd->genre }}
                            </span>
                        @endif

                    </div>

                </div>

                <!-- TRÁILER OFICIAL -->
                <div class="bg-[#151518] border border-white/10 rounded-2xl p-6 md:p-8 shadow-xl">

                    <h2 class="text-xl md:text-2xl font-black flex items-center gap-2 mb-6">
                        <span class="text-red-600">|</span> Tráiler oficial
                    </h2>

                    @if ($trailer)
                        <div class="aspect-video w-full rounded-xl overflow-hidden border border-white/10 bg-black">

                            <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $trailer }}"
                                title="Tráiler de {{ $movieBd->title }}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                            </iframe>

                        </div>
                    @else
                        <div class="bg-[#09090b] border border-white/5 rounded-xl p-8 text-center">
                            <p class="text-gray-500 text-sm">
                                🎬 No hay tráiler disponible para esta película.
                            </p>
                        </div>
                    @endif

                </div>

                <!-- SINOPSIS -->
                <div class="bg-[#151518] border border-white/10 rounded-2xl p-6 md:p-8 shadow-xl">

                    <h2 class="text-xl md:text-2xl font-black flex items-center gap-2 mb-4">
                        <span class="text-red-600">|</span> Sinopsis
                    </h2>

                    <p class="text-gray-300 text-base leading-relaxed">
                        {{ $movieBd->overview ?: 'Sin descripción disponible para esta película.' }}
                    </p>

                </div>

            </div>

        </div>

    </main>

    @include('layouts.footer')

</body>

</html>
