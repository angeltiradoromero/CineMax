<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Cinemax') }}</title>

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="bg-[#09090b] text-white font-sans antialiased min-h-screen flex flex-col">

    @include('layouts.navigation')

    <main class="max-w-6xl mx-auto px-6 py-14 flex-grow w-full text-center">

        <header class="mb-12 after:content-[''] after:block after:w-20 after:h-1 after:bg-red-600 after:mx-auto after:mt-8 after:rounded-full">
            <span class="block text-red-500 uppercase tracking-[0.25em] text-xs font-bold mb-3">
                Tu cine de siempre
            </span>

            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black tracking-tight">
                BIENVENIDO A <span class="text-red-600">CINEMAX</span>
            </h1>

            <p class="mt-5 max-w-2xl mx-auto text-gray-400 text-base md:text-lg leading-relaxed">
                Consulta los horarios de esta semana, elige tus butacas sin colas y disfruta de los mejores estrenos en pantalla grande
            </p>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left">

            <article class="relative group overflow-hidden rounded-2xl bg-[#151518] border border-white/10 shadow-lg hover:border-red-600/50 transition duration-300 h-72 after:content-[''] after:absolute after:inset-0 after:bg-gradient-to-t after:from-black/90 after:via-black/30 after:to-transparent">
                <img src="{{ asset('images/inicio_1.jpg') }}" alt="Salas Dolby Atmos"
                    class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                <div class="relative z-10 -mt-28 px-5">
                    <span class="text-red-500 text-xs font-bold uppercase tracking-wider">Imagen y sonido</span>
                    <h2 class="text-white font-black text-lg mt-0.5">Salas Dolby Atmos</h2>
                    <p class="text-gray-300 text-xs mt-1">Sonido envolvente para que sientas cada escena como si estuvieras dentro.</p>
                </div>
            </article>

            <article class="relative group overflow-hidden rounded-2xl bg-[#151518] border border-white/10 shadow-lg hover:border-red-600/50 transition duration-300 h-72 after:content-[''] after:absolute after:inset-0 after:bg-gradient-to-t after:from-black/90 after:via-black/30 after:to-transparent">
                <img src="{{ asset('images/inicio_2.jpg') }}" alt="Zona Bar y Palomitas"
                    class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                <div class="relative z-10 -mt-28 px-5">
                    <span class="text-red-500 text-xs font-bold uppercase tracking-wider">Para picar</span>
                    <h2 class="text-white font-black text-lg mt-0.5">Palomitas y bebidas</h2>
                    <p class="text-gray-300 text-xs mt-1">Recién hechas antes de cada sesión, con combos para compartir.</p>
                </div>
            </article>

            <article class="relative group overflow-hidden rounded-2xl bg-[#151518] border border-white/10 shadow-lg hover:border-red-600/50 transition duration-300 h-72 after:content-[''] after:absolute after:inset-0 after:bg-gradient-to-t after:from-black/90 after:via-black/30 after:to-transparent">
                <img src="{{ asset('images/inicio_3.webp') }}" alt="Butacas y salas"
                    class="w-full h-full object-cover object-top scale-110 transition duration-500 group-hover:scale-125">
                <div class="relative z-10 -mt-28 px-5">
                    <span class="text-red-500 text-xs font-bold uppercase tracking-wider">Comodidad</span>
                    <h2 class="text-white font-black text-lg mt-0.5">Butacas amplias</h2>
                    <p class="text-gray-300 text-xs mt-1">Espacio de sobra para estirar las piernas y ver la película a gusto.</p>
                </div>
            </article>

        </section>

        <a href="{{ route('movies.now-playing') }}"
            class="mt-10 inline-flex items-center gap-2 px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-black rounded-xl transition shadow-lg shadow-red-900/30 text-sm uppercase tracking-wider">
            Ver cartelera de hoy
        </a>

        <section class="mt-20 bg-gradient-to-r from-[#151518] via-[#1c1215] to-[#151518] border border-red-600/30 rounded-3xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8 text-left shadow-2xl">
            <div>
                <span class="bg-red-600 text-white text-[11px] font-black uppercase tracking-wider px-3 py-1 rounded-md">
                    Día del espectador
                </span>
                <h2 class="text-2xl md:text-4xl font-black mt-4">
                    Miércoles de <span class="text-red-500">Cine a mitad de precio</span>
                </h2>
                <p class="text-gray-400 text-sm md:text-base mt-2 max-w-xl">
                    Crea tu cuenta en un minuto para reservar entradas con descuento cada miércoles y enterarte antes que nadie de los próximos estrenos.
                </p>
            </div>

            @guest
                <a href="{{ route('register') }}"
                    class="shrink-0 px-7 py-4 bg-red-600 hover:bg-red-700 text-white font-black rounded-xl transition shadow-lg shadow-red-900/30 text-sm uppercase tracking-wider">
                    Regístrate gratis
                </a>
            @else
                <a href="{{ route('movies.now-playing') }}"
                    class="shrink-0 px-7 py-4 bg-red-600 hover:bg-red-700 text-white font-black rounded-xl transition shadow-lg shadow-red-900/30 text-sm uppercase tracking-wider">
                    Reservar entradas
                </a>
            @endguest
        </section>

    </main>

    @include('layouts.footer')

</body>

</html>
