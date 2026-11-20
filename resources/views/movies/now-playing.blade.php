
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'MiCartelera') }}</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-[#09090b] text-white font-sans antialiased min-h-screen">

    @include('layouts.navigation')

    <main class="max-w-7xl mx-auto px-6 py-12">

        <div class="text-center mb-12">

            <h1 class="text-3xl md:text-5xl font-black tracking-tight">
                <span class="text-red-600">CARTELERA</span>
            </h1>

            <p class="mt-5 max-w-2xl mx-auto text-gray-400 text-base md:text-lg leading-relaxed">
                Descubre las películas que están en cartelera, echa un vistazo a sus tráilers
                y reserva tus butacas para tu próxima sesión de cine
            </p>

            <div class="w-20 h-1 bg-red-600 mx-auto mt-8 rounded-full"></div>

        </div>

        <div class="max-w-xl mx-auto mb-14 bg-[#151518] border border-white/10 rounded-2xl p-5 shadow-xl">

            @include('layouts/search')

        </div>

        <div class="mb-8">

            <h2 class="text-2xl md:text-3xl font-bold">
                <span class="text-red-600">|</span>
                Ahora en cartelera
            </h2>

            <p class="text-gray-500 text-sm mt-2">
                Estas son las películas que puedes disfrutar ahora mismo.
            </p>

        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-7 items-stretch">

            @forelse($moviesBd as $movieBd)

                <div data-id="{{ $movieBd->id }}"
                    class="movie group flex flex-col bg-[#151518] border border-white/5 rounded-xl shadow-lg overflow-hidden cursor-pointer hover:shadow-red-900/20 hover:border-red-600/40 hover:-translate-y-2 transition duration-300">

                    <div class="relative overflow-hidden">

                        <img
                            src="https://image.tmdb.org/t/p/w500{{ $movieBd->poster_path }}"
                            alt="{{ $movieBd->title }}"
                            loading="lazy"
                            class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-80"></div>

                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">

                            <span class="btnComprar text-white text-base font-bold bg-red-600 hover:bg-red-700 px-6 py-3 rounded-lg shadow-xl transition">
                                🎟 Comprar entradas
                            </span>

                        </div>

                        <div class="absolute top-3 left-3">
                            <span class="bg-red-600 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-md shadow-lg">
                                En cartelera
                            </span>
                        </div>

                    </div>

                    <div class="p-4 flex flex-col flex-grow justify-center">

                        <h3 class="text-white font-bold text-sm md:text-base truncate group-hover:text-red-500 transition">
                            {{ $movieBd->title }}
                        </h3>

                        <p class="text-gray-500 text-xs mt-2">
                            ¡Ya puedes reservar tu sesión!
                        </p>

                    </div>

                </div>

            @empty

                <div class="col-span-full text-center py-20 bg-[#151518] border border-white/10 rounded-xl">

                    <div class="text-5xl mb-5">
                        🎬
                    </div>

                    <h3 class="text-xl md:text-2xl font-bold text-white">
                        Vaya, parece que no hay películas
                    </h3>

                    <p class="text-gray-400 mt-3 text-sm">
                        Ahora mismo no tenemos películas disponibles. ¡Vuelve pronto!
                    </p>

                </div>

            @endforelse

        </div>

    </main>

    @include('layouts.footer')

</body>

</html>
