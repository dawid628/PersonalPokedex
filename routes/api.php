<?php

use App\Http\Controllers\BannedPokemonController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\PokemonInfoController;
use Illuminate\Support\Facades\Route;


Route::post('/info', [PokemonInfoController::class, 'index']);

Route::middleware('authorize')->group(function () {
    Route::prefix('banned')->group(function () {
        Route::get('/', [BannedPokemonController::class, 'index']);
        Route::post('/', [BannedPokemonController::class, 'store']);
        Route::get('/{name}', [BannedPokemonController::class, 'show']);
        Route::delete('/{name}', [BannedPokemonController::class, 'destroy']);
    });

    Route::prefix('pokemons')->group(function () {
        Route::get('/', [PokemonController::class, 'index']);
        Route::post('/', [PokemonController::class, 'store']);
        Route::get('/{name}', [PokemonController::class, 'show']);
        Route::delete('/{name}', [PokemonController::class, 'destroy']);
    });
});
