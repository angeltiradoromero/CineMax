<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Cinemax') }} - Mis Favoritos</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-[#09090b] text-white font-sans antialiased min-h-screen flex flex-col">

    @include('layouts.navigation')

    <main class="max-w-7xl mx-auto px-6 py-12 flex-grow w-full">
        <!-- BUSCADOR -->
        <div class="max-w-xl mx-auto mb-14 bg-[#151518] border border-white/10 rounded-2xl p-5 shadow-xl">

            @include('layouts/search')

        </div>
        <!-- CABECERA -->
        <section class="text-center mb-14">

            <span class="inline-block text-red-500 uppercase tracking-[0.35em] text-xs font-bold mb-4">
                ❤️ Tu colección personal
            </span>

            <h1 class="text-4xl md:text-6xl font-black tracking-tight">
                MIS <span class="text-red-600">FAVORITOS</span>
            </h1>

            <p class="mt-5 max-w-2xl mx-auto text-gray-400 text-base md:text-lg leading-relaxed">
                Todas las películas que te han conquistado en un solo lugar.
                Guarda tus favoritas y tenlas siempre a mano.
            </p>

            <div class="w-24 h-1 bg-red-600 mx-auto mt-8 rounded-full"></div>

        </section>

        <!-- RESUMEN -->
        <section class="max-w-5xl mx-auto mb-14 bg-[#151518] border border-white/10 rounded-2xl p-8 shadow-xl">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-center">

                <div>
                    <p class="text-3xl md:text-4xl font-black text-red-600">
                        {{ $favorites->count() }}
                    </p>
                    <p class="text-xs uppercase tracking-widest text-gray-400 mt-2">
                        Películas favoritas
                    </p>
                </div>

                <div>
                    <p class="text-3xl md:text-4xl font-black text-red-600">
                        ❤️
                    </p>
                    <p class="text-xs uppercase tracking-widest text-gray-400 mt-2">
                        Tu colección Cinemax
                    </p>
                </div>

            </div>

        </section>


        <!-- LISTADO DE FAVORITOS -->
        <section class="max-w-7xl mx-auto">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-8">

                <div>
                    <h2 class="text-2xl md:text-3xl font-black">
                        Tus películas guardadas
                    </h2>

                    <p class="text-gray-400 text-sm mt-2">
                        Películas que has seleccionado como favoritas.
                    </p>
                </div>

                <a href="{{ route('movies.now-playing') }}"
                    class="text-red-500 hover:text-red-400 font-semibold text-sm transition">
                    Explorar cartelera →
                </a>

            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">

                @forelse ($favorites as $movie)
                    <article
                        class="group bg-[#151518] border border-white/10 rounded-2xl overflow-hidden hover:border-red-600/50 hover:-translate-y-1 transition duration-300 shadow-lg">

                        <!-- PÓSTER -->
                        <div class="relative aspect-[2/3] overflow-hidden bg-[#09090b]">

                            @if ($movie->poster_path)
                                <img src="https://image.tmdb.org/t/p/w500{{ $movie->poster_path }}"
                                    alt="{{ $movie->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-5xl">
                                    🎬
                                </div>
                            @endif

                            <!-- ETIQUETA FAVORITO -->
                            <div
                                class="absolute top-3 right-3 bg-red-600 text-white rounded-full w-9 h-9 flex items-center justify-center shadow-lg">
                                ❤️
                            </div>

                            <!-- GRADIENTE -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-70">
                            </div>

                        </div>

                        <!-- INFORMACIÓN -->
                        <div class="p-4">

                            <h3 class="font-bold text-white text-base truncate group-hover:text-red-500 transition">
                                {{ $movie->title }}
                            </h3>

                            @if (!empty($movie->release_date))
                                <p class="text-gray-500 text-xs mt-2">
                                    {{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}
                                </p>
                            @endif

                            <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between">

                                <span class="text-xs text-gray-400">
                                    Mi favorito
                                </span>

                                <span class="text-red-500 text-sm">
                                    ♥
                                </span>

                            </div>

                        </div>

                    </article>

                @empty

                    <!-- SIN FAVORITOS -->
                    <div class="col-span-full bg-[#151518] border border-white/10 rounded-2xl p-12 md:p-16 text-center">

                        <div
                            class="w-20 h-20 mx-auto bg-red-600/10 rounded-full flex items-center justify-center text-4xl mb-6">
                            ❤️
                        </div>

                        <h3 class="text-2xl font-black text-white">
                            Todavía no tienes favoritos
                        </h3>

                        <p class="text-gray-400 mt-3 max-w-md mx-auto text-sm leading-relaxed">
                            Explora nuestra cartelera y guarda las películas que más te gusten.
                            Aquí aparecerán todas tus favoritas.
                        </p>

                        <a href="{{ route('movies.now-playing') }}"
                            class="inline-block mt-8 bg-red-600 hover:bg-red-700 text-white font-bold px-8 py-3.5 rounded-xl transition shadow-lg shadow-red-600/20">
                            Explorar cartelera
                        </a>

                    </div>
                @endforelse

            </div>

        </section>

        <!-- CTA -->
        <section
            class="max-w-5xl mx-auto mt-20 text-center bg-gradient-to-r from-red-950/40 via-[#151518] to-red-950/40 border border-red-600/30 rounded-2xl p-10">

            <h2 class="text-2xl md:text-3xl font-black">
                ¿BUSCAS TU PRÓXIMA PELÍCULA?
            </h2>

            <p class="text-gray-400 mt-3 max-w-xl mx-auto text-sm md:text-base">
                Descubre los últimos estrenos y añade nuevas películas a tu colección
            </p>

            <a href="{{ route('movies.now-playing') }}"
                class="inline-block mt-6 bg-red-600 hover:bg-red-700 text-white font-bold px-8 py-3.5 rounded-xl transition shadow-lg shadow-red-600/20">
                Ver cartelera
            </a>

        </section>

    </main>

    @include('layouts.footer')

</body>

</html>
