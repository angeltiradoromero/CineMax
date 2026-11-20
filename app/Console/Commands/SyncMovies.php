<?php

namespace App\Console\Commands;

use App\Services\Movies\MovieService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('sync-movies')]
#[Description('Sincroniza películas desde TMDB')]
class SyncMovies extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(MovieService $movieService)
    {
        $movieService->getNowPlaying();
        $movieService->getUpcomingMovies();

        $this->info("Peliculas sincronizadas correctamente");
        return Command::SUCCESS;
    }
}
