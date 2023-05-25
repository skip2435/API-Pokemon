<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PokemonController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('pokemon');
});

Route::get('/pokemon/{generation}', [PokemonController::class, 'index']);

// Add this line to provide a route for the search page.
Route::get('/poke-search', function () {
    return view('poke-search');
});

// Add this line to provide a route for getting individual Pokemon data.
Route::get('/pokemon/{pokemon}', [PokemonController::class, 'show']);

Route::get('/encounters', function () {
    return view('encounters');
});