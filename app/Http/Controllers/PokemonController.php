<?php

namespace App\Http\Controllers;

use App\Services\PokeApiService;
use Illuminate\Http\JsonResponse;


class PokemonController extends Controller
{
    public function __construct(private readonly PokeApiService $pokeApiService)
    {}

    public function index(): JsonResponse
    {
        $allPokemons = $this->pokeApiService->getAll();

        if (!$allPokemons) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to connect to PokeAPI'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'PokeAPI connection successful!',
            'total' => count($allPokemons['results']),
            'pokemons' => $allPokemons['results'],
            'pagination' => [
                'next' => $allPokemons['next'],
                'previous' => $allPokemons['previous']
            ]
        ]);
    }
}
