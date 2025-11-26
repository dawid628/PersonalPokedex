<?php

use App\Http\Controllers\BannedPokemonController;
use App\Http\Controllers\PokemonInfoController;
use Illuminate\Support\Facades\Route;


Route::post('/info', [PokemonInfoController::class, 'index']);

Route::middleware('authorize')->prefix('banned')->group(function () {
    Route::get('/', [BannedPokemonController::class, 'index']);
    Route::post('/', [BannedPokemonController::class, 'store']);
    Route::get('/{name}', [BannedPokemonController::class, 'show']);
    Route::delete('/{name}', [BannedPokemonController::class, 'destroy']);
});
