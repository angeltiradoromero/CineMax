<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Cinemax') }} - Sobre Nosotros</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Vite -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* CONTENEDOR DEL MAPA */
        #contenedor-mapa {
            height: 550px !important;
            width: 100% !important;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            z-index: 1;
            background: #151518;
        }

        /* CORREGIR CONFLICTOS CON TAILWIND */
        #contenedor-mapa img {
            max-width: none !important;
            max-height: none !important;
        }

        #contenedor-mapa .leaflet-tile {
            width: 256px;
            height: 256px;
        }

        #contenedor-mapa .leaflet-pane {
            position: absolute;
        }

        #contenedor-mapa .leaflet-marker-icon,
        #contenedor-mapa .leaflet-marker-shadow {
            display: block;
        }

        #contenedor-mapa .leaflet-control {
            z-index: 800;
        }

        /* BOTÓN CÓMO LLEGAR */
        .btn-como-llegar {
            display: inline-block;
            margin-top: 8px;
            background-color: #dc2626;
            color: white !important;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 700;
            font-family: sans-serif;
            font-size: 13px;
            transition: background-color 0.2s;
        }

        .btn-como-llegar:hover {
            background-color: #b91c1c;
        }
    </style>
</head>

<body class="bg-[#09090b] text-white font-sans antialiased min-h-screen flex flex-col">

    @include('layouts.navigation')

    <!-- CONTENIDO PRINCIPAL -->
    <main class="max-w-7xl mx-auto px-6 py-12 flex-grow w-full">

        <!-- CABECERA / PRESENTACIÓN -->
        <div class="text-center mb-14">

            <span class="inline-block text-red-500 uppercase tracking-[0.35em] text-xs font-bold mb-4">
                📍 Encuentra tu cine más cercano
            </span>

            <h1 class="text-4xl md:text-6xl font-black tracking-tight">
                BIENVENIDOS A <span class="text-red-600">CINEMAX</span>
            </h1>

            <p class="mt-5 max-w-2xl mx-auto text-gray-400 text-base md:text-lg leading-relaxed">
                Nacimos con una idea sencilla: devolver la magia de la gran pantalla con la mejor tecnología de imagen,
                sonido envolvente y la comodidad que te mereces.
            </p>

            <div class="w-24 h-1 bg-red-600 mx-auto mt-8 rounded-full"></div>

        </div>

        <!-- CIFRAS DESTACADAS -->
        <section class="max-w-5xl mx-auto mb-14 bg-[#151518] border border-white/10 rounded-2xl p-8 shadow-xl">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">

                <div>
                    <p class="text-3xl md:text-4xl font-black text-red-600">8</p>
                    <p class="text-xs uppercase tracking-widest text-gray-400 mt-1">
                        Salas digitales
                    </p>
                </div>

                <div>
                    <p class="text-3xl md:text-4xl font-black text-red-600">+1.200</p>
                    <p class="text-xs uppercase tracking-widest text-gray-400 mt-1">
                        Butacas VIP
                    </p>
                </div>

                <div>
                    <p class="text-3xl md:text-4xl font-black text-red-600">4K</p>
                    <p class="text-xs uppercase tracking-widest text-gray-400 mt-1">
                        Proyección Láser
                    </p>
                </div>

                <div>
                    <p class="text-3xl md:text-4xl font-black text-red-600">365</p>
                    <p class="text-xs uppercase tracking-widest text-gray-400 mt-1">
                        Días de cine al año
                    </p>
                </div>

            </div>
        </section>

        <!-- CONTENEDOR DEL MAPA -->
        <section class="max-w-5xl mx-auto">

            <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

                <h2 class="text-xl font-bold text-white">
                    Dónde estamos
                </h2>

                <span class="text-sm text-gray-400">
                    Haz clic en el marcador para ver cómo llegar
                </span>

            </div>

            <div class="bg-[#151518] border border-white/10 rounded-2xl p-4 md:p-6 shadow-2xl">

                <!-- MAPA -->
                <div id="contenedor-mapa"></div>

            </div>
        </section>

        <!-- LA EXPERIENCIA CINEMAX -->
        <section class="max-w-5xl mx-auto mt-20">

            <div class="text-center mb-12">

                <span class="text-red-500 uppercase tracking-[0.25em] text-xs font-bold">
                    ¿Por qué elegirnos?
                </span>

                <h2 class="text-3xl md:text-4xl font-black mt-2">
                    MÁS QUE UNA SALA DE CINE
                </h2>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- TARJETA 1 -->
                <div
                    class="bg-[#151518] border border-white/10 rounded-2xl p-6 hover:border-red-600/50 transition duration-300">

                    <div
                        class="w-12 h-12 bg-red-600/10 text-red-500 rounded-xl flex items-center justify-center text-2xl mb-4">
                        🎬
                    </div>

                    <h3 class="text-xl font-bold mb-2">
                        Proyección 4K Láser
                    </h3>

                    <p class="text-gray-400 text-sm leading-relaxed">
                        Disfruta de colores más vivos, negros profundos y una nitidez extrema en cada fotograma de tus
                        estrenos favoritos.
                    </p>

                </div>

                <!-- TARJETA 2 -->
                <div
                    class="bg-[#151518] border border-white/10 rounded-2xl p-6 hover:border-red-600/50 transition duration-300">

                    <div
                        class="w-12 h-12 bg-red-600/10 text-red-500 rounded-xl flex items-center justify-center text-2xl mb-4">
                        🔊
                    </div>

                    <h3 class="text-xl font-bold mb-2">
                        Sonido Envolvente
                    </h3>

                    <p class="text-gray-400 text-sm leading-relaxed">
                        Nuestras salas cuentan con acústica calibrada y sonido multidimensional para que sientas cada
                        escena en primera persona.
                    </p>

                </div>

                <!-- TARJETA 3 -->
                <div
                    class="bg-[#151518] border border-white/10 rounded-2xl p-6 hover:border-red-600/50 transition duration-300">

                    <div
                        class="w-12 h-12 bg-red-600/10 text-red-500 rounded-xl flex items-center justify-center text-2xl mb-4">
                        🍿
                    </div>

                    <h3 class="text-xl font-bold mb-2">
                        Confort y Bar
                    </h3>

                    <p class="text-gray-400 text-sm leading-relaxed">
                        Butacas ergonómicas con amplio espacio entre filas y palomitas recién hechas listas antes de que
                        se apaguen las luces.
                    </p>

                </div>

            </div>
        </section>

        <!-- CALL TO ACTION -->
        <section
            class="max-w-5xl mx-auto mt-20 text-center bg-gradient-to-r from-red-950/40 via-[#151518] to-red-950/40 border border-red-600/30 rounded-2xl p-10">

            <h2 class="text-2xl md:text-3xl font-black">
                ¿LISTO PARA TU PRÓXIMA PELÍCULA?
            </h2>

            <p class="text-gray-400 mt-3 max-w-xl mx-auto text-sm md:text-base">
                Consulta los horarios de hoy, elige tus asientos favoritos en el mapa de sala y evita colas en taquilla.
            </p>

            <div class="mt-6">

                <a href="{{ route('movies.now-playing') }}"
                    class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold px-8 py-3.5 rounded-xl transition shadow-lg shadow-red-600/20">
                    Ver Cartelera
                </a>

            </div>
        </section>

    </main>

    @include('layouts.footer')

    <!-- LEAFLET JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- TU ARCHIVO JAVASCRIPT INDEPENDIENTE -->
    <script src="{{ asset('js/mapa.js') }}"></script>

</body>

</html>
