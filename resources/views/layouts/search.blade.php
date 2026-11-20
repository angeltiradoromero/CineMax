<form action="{{ route('movies.search') }}" method="GET" class="relative w-full">

    <div class="flex items-center gap-3 w-full">
        <input type="text" name="search" id="search" data-url="{{ route('movies.suggestions') }}"
            placeholder="Buscar películas..." autocomplete="off"
            class="w-full flex-1 min-w-0 bg-[#09090b] text-white placeholder-gray-400 border border-white/10 rounded-lg px-4 py-3 focus:outline-none focus:border-red-600 transition">

        <button type="submit"
            class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3 rounded-lg transition shadow-lg shadow-red-900/20 shrink-0 cursor-pointer">
            Buscar
        </button>
    </div>

    <ul id="suggestions"></ul>

</form>
