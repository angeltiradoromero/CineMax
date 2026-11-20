<nav x-data="{ open: false }" class="w-full bg-[#111113] border-b border-white/10 sticky top-0 z-50">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- IZQUIERDA: LOGO Y NAVEGACIÓN -->
            <div class="flex items-center gap-8">

                <a href="{{ route('welcome') }}" class="text-2xl font-black tracking-tight text-white shrink-0">
                    🎬 Cine<span class="text-red-600">Max</span>
                </a>

                <div class="hidden md:flex items-center gap-6">

                    <a href="{{ route('welcome') }}"
                        class="text-sm font-semibold transition {{ request()->routeIs('welcome') ? 'text-red-500' : 'text-gray-300 hover:text-white' }}">
                        Inicio
                    </a>

                    <a href="{{ route('movies.now-playing') }}"
                        class="text-sm font-semibold transition {{ request()->routeIs('movies.now-playing') ? 'text-red-500' : 'text-gray-300 hover:text-white' }}">
                        Cartelera
                    </a>

                    <a href="{{ route('movies.upcoming-movies') }}"
                        class="text-sm font-semibold transition {{ request()->routeIs('movies.upcoming-movies') ? 'text-red-500' : 'text-gray-300 hover:text-white' }}">
                        Próximos estrenos
                    </a>

                    <a href="{{ route('about-us') }}"
                        class="text-sm font-semibold transition {{ request()->routeIs('about-us') ? 'text-red-500' : 'text-gray-300 hover:text-white' }}">
                        Sobre nosotros
                    </a>

                </div>
            </div>

            <!-- DERECHA: CARRITO Y USUARIO -->
            <div class="hidden md:flex items-center gap-4">

                @auth

                    <!-- CARRITO -->
                    <a href="{{ route('bookings.cart.index') }}"
                        class="relative inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-white/10
                        bg-[#151518] text-gray-300 text-sm font-semibold
                        hover:text-white hover:border-red-600/50 hover:bg-red-600/10 transition
                        {{ request()->routeIs('bookings.cart*') || request()->routeIs('bookings.payment') ? 'text-red-500 border-red-600/50' : '' }}">

                        <span class="text-lg">🛒</span>
                        <span>Carrito</span>

                    </a>

                    <!-- DESPLEGABLE DE USUARIO -->
                    <x-dropdown align="right" width="48">

                        <x-slot name="trigger">

                            <button
                                class="inline-flex items-center gap-2 px-4 py-2 border border-white/10 text-sm font-semibold rounded-lg text-gray-200 bg-[#151518] hover:border-red-600/50 hover:text-white focus:outline-none transition cursor-pointer">

                                <span>👤 {{ Auth::user()->name }}</span>

                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">

                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />

                                </svg>

                            </button>

                        </x-slot>

                        <x-slot name="content">

                            <div class="bg-[#151518] border border-white/10 rounded-md py-1 text-gray-200">

                                <a href="{{ route('bookings.tickets') }}"
                                    class="block px-4 py-2 text-sm font-semibold text-white hover:bg-white/10 hover:text-red-500 transition">
                                    🎟️ Mis entradas
                                </a>

                                <a href="{{ route('favorites.list') }}"
                                    class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/10 hover:text-red-500 transition">
                                    ❤️ Mis favoritos
                                </a>

                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/10 hover:text-white transition">
                                    ⚙️ Mi perfil
                                </a>

                                <div class="border-t border-white/10 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-red-600/10 transition cursor-pointer">
                                        🚪 Cerrar sesión
                                    </button>
                                </form>

                            </div>

                        </x-slot>

                    </x-dropdown>
                @else
                    <!-- VISITANTE -->
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                            class="text-sm font-semibold text-gray-300 hover:text-red-500 transition">
                            Iniciar Sesión
                        </a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center px-5 py-2.5 bg-red-600 rounded-lg font-bold text-sm text-white hover:bg-red-700 transition shadow-lg shadow-red-900/20">
                            Registrarse
                        </a>
                    @endif

                @endauth

            </div>

            <!-- BOTÓN HAMBURGUESA -->
            <div class="-me-2 flex items-center md:hidden">

                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-white bg-[#151518] border border-white/10 focus:outline-none transition">

                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">

                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />

                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />

                    </svg>

                </button>

            </div>

        </div>
    </div>

    <!-- MENÚ MÓVIL -->
    <div :class="{ 'block': open, 'hidden': !open }"
        class="hidden md:hidden bg-[#151518] border-t border-white/10 px-6 py-4 space-y-2">

        <a href="{{ route('welcome') }}" class="block py-2 text-sm font-semibold text-gray-300 hover:text-red-500">
            Inicio
        </a>

        <a href="{{ route('movies.now-playing') }}"
            class="block py-2 text-sm font-semibold text-gray-300 hover:text-red-500">
            Cartelera
        </a>

        <a href="{{ route('movies.upcoming-movies') }}"
            class="block py-2 text-sm font-semibold text-gray-300 hover:text-red-500">
            Próximos estrenos
        </a>

        <a href="{{ route('about-us') }}" class="block py-2 text-sm font-semibold text-gray-300 hover:text-red-500">
            Sobre nosotros
        </a>

        <div class="pt-3 border-t border-white/10">

            @auth

                <!-- USUARIO -->
                <div class="mb-3">
                    <div class="font-bold text-sm text-white">
                        {{ Auth::user()->name }}
                    </div>

                    <div class="text-xs text-gray-400">
                        {{ Auth::user()->email }}
                    </div>
                </div>

                <!-- CARRITO MÓVIL -->
                <a href="{{ route('bookings.cart.index') }}"
                    class="flex items-center gap-2 py-2 px-3 mb-2 rounded-lg bg-red-600/10 border border-red-600/20 text-red-400 font-bold text-sm hover:bg-red-600/20 transition">
                    🛒 Mi carrito
                </a>

                <a href="{{ route('bookings.tickets') }}"
                    class="block py-1.5 text-sm font-semibold text-gray-300 hover:text-red-500">
                    🎟️ Mis entradas
                </a>

                <a href="{{ route('favorites.list') }}" class="block py-1.5 text-sm text-gray-300 hover:text-white">
                    ❤️ Mis favoritos
                </a>

                <a href="{{ route('profile.edit') }}" class="block py-1.5 text-sm text-gray-300 hover:text-white">
                    ⚙️ Mi perfil
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf

                    <button type="submit" class="text-sm font-semibold text-red-400 hover:text-red-300 transition">
                        🚪 Cerrar sesión
                    </button>
                </form>
            @else
                <div class="flex flex-col gap-2 pt-2">

                    <a href="{{ route('login') }}"
                        class="block text-center py-2 text-sm font-semibold text-gray-300 border border-white/10 rounded-lg">
                        Iniciar Sesión
                    </a>

                    <a href="{{ route('register') }}"
                        class="block text-center py-2 text-sm font-bold text-white bg-red-600 rounded-lg">
                        Registrarse
                    </a>

                </div>

            @endauth

        </div>

    </div>

</nav>
