<?php

use App\Http\Controllers\BannedPokemonController;
use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;


Route::get('/pokemons', [PokemonController::class, 'index']);

Route::apiResource('banned', BannedPokemonController::class)->parameters([
    'banned' => 'name'
]);
