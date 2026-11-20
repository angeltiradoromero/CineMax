
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Resultados de búsqueda - {{ config('app.name', 'MiCartelera') }}</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-[#09090b] text-white font-sans antialiased min-h-screen flex flex-col">

    @include('layouts.navigation')

    <main class="max-w-7xl mx-auto px-6 py-12 flex-grow w-full">

        <div class="mb-10">

            <h1 class="text-3xl md:text-4xl font-black text-white mt-3">
                Resultados de búsqueda
            </h1>

            <p class="text-gray-400 mt-3">
                Resultados para:
                <span class="text-red-500 font-semibold">{{ $text }}</span>
            </p>

            <div class="w-20 h-1 bg-red-600 mt-6 rounded-full"></div>

        </div>

        @if ($moviesBd->isEmpty())

            <div class="text-center py-20 bg-[#151518] border border-white/10 rounded-2xl">

                <div class="text-5xl mb-5">
                    🎬
                </div>

                <h2 class="text-xl md:text-2xl font-bold text-white">
                    No hemos encontrado películas
                </h2>

                <p class="text-gray-400 mt-3">
                    Prueba con otro título o comprueba que lo has escrito correctamente.
                </p>

                <a href="{{ route('movies.now-playing') }}"
                    class="inline-block mt-7 px-6 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition">
                    Volver a la cartelera
                </a>

            </div>

        @else

            <p class="text-gray-400 text-sm mb-7">
                Hemos encontrado
                <span class="text-white font-bold">{{ $moviesBd->count() }}</span>
                películas.
            </p>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-7 items-stretch">

                @foreach ($moviesBd as $movieBd)

                    <a href="{{ route('movies.movie-details', $movieBd->id) }}"
                        class="group flex flex-col bg-[#151518] border border-white/5 rounded-xl shadow-lg overflow-hidden hover:shadow-red-900/20 hover:border-red-600/40 hover:-translate-y-2 transition duration-300">

                        <div class="relative overflow-hidden">

                            <img src="https://image.tmdb.org/t/p/w500{{ $movieBd->poster_path }}"
                                alt="{{ $movieBd->title }}"
                                loading="lazy"
                                class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">

                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>

                            <div class="absolute top-3 left-3">
                                <span class="bg-red-600 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-md">
                                    Película
                                </span>
                            </div>

                        </div>

                        <div class="p-4 flex flex-col flex-grow">

                            <h2 class="text-white font-bold text-sm md:text-base group-hover:text-red-500 transition">
                                {{ $movieBd->title }}
                            </h2>

                            @if ($movieBd->release_date)
                                <p class="text-gray-500 text-xs mt-2">
                                    Una película de {{ substr($movieBd->release_date, 0, 4) }}
                                </p>
                            @endif

                            <p class="text-gray-500 text-xs mt-3">
                                Ver detalles de la película →
                            </p>

                        </div>

                    </a>

                @endforeach

            </div>

        @endif

    </main>

    @include('layouts.footer')

</body>

</html>
