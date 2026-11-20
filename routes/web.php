<?php

use App\Http\Controllers\Cinema\BookingController;
use App\Http\Controllers\Movies\FavoriteController;
use App\Http\Controllers\Movies\MovieController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//1. RUTAS PÚBLICAS

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/cartelera', [MovieController::class, 'getNowPlaying'])->name('movies.now-playing');
Route::get('/pelicula/{id}', [MovieController::class, 'movieDetails'])->name('movies.movie-details');
Route::get('/sugerencias', [MovieController::class, 'getSuggestions'])->name('movies.suggestions');
Route::get('/buscar', [MovieController::class, 'searchMovie'])->name('movies.search');
Route::get('/proximos-estrenos', [MovieController::class, 'upcomingMovies'])->name('movies.upcoming-movies');
Route::view('/sobre-nosotros', 'about-us')->name('about-us');

// 2. RUTAS SOLO CON SESIÓN INICIADA (No requieren correo confirmado)

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 3. RUTAS CON USUARIO Y CORREO CONFIRMADO ('auth' y 'verified')


Route::middleware(['auth', 'verified'])->group(function () {
    /*
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    */

    // Favoritos
    Route::get('/favoritos', [FavoriteController::class, 'listFavorites'])->name('favorites.list');
    Route::post('/favorites/{movie}/toggle', [FavoriteController::class, 'toggleFavorite'])->name('favorites.toggle');

    //Butacas, carrito, pago y entradas
    Route::get('/sesion/{session}/butacas', [BookingController::class, 'showSeats'])->name('sessions.seats');
    Route::post('/sesion/{session}/comprar', [BookingController::class, 'store'])->name('sessions.book');

    Route::get('/carrito', [BookingController::class, 'myCart'])->name('bookings.cart.index');
    Route::get('/carrito/{bookingGroup}', [BookingController::class, 'cart'])->name('bookings.cart');

    Route::get('/pago/{bookingGroup}', [BookingController::class, 'payment'])->name('bookings.payment');
    Route::post('/pago/{bookingGroup}/confirmar', [BookingController::class, 'confirmPayment'])->name('bookings.confirm-payment');

    Route::get('/mis-entradas', [BookingController::class, 'myTickets'])->name('bookings.tickets');
    Route::get('/mis_entradas', [MovieController::class, 'mostrarPelicula'])->name('mis_entradas');
});

require __DIR__ . '/auth.php';
