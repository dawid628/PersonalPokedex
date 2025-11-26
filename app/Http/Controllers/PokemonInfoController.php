<?php

namespace App\Http\Controllers;

use App\Http\Requests\PokemonInfoRequest;
use App\Services\PokemonInfoService;
use Illuminate\Http\JsonResponse;


/**
 * @OA\Tag(
 *     name="Pokemon Info",
 *     description="API endpoints for getting pokemon information"
 * )
 */
class PokemonInfoController extends Controller
{
    public function __construct(
        private readonly PokemonInfoService $service
    ) {}

    /**
     * @OA\Post(
     *     path="/api/info",
     *     tags={"Pokemon Info"},
     *     summary="Get information about multiple pokemons",
     *     description="Returns detailed information about requested pokemons. Banned pokemons are excluded from results.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pokemons"},
     *             @OA\Property(
     *                 property="pokemons",
     *                 type="array",
     *                 description="List of pokemon names (max 50)",
     *                 @OA\Items(type="string"),
     *                 example={"pikachu", "charizard", "bulbasaur"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="total_requested", type="integer", example=3),
     *             @OA\Property(property="total_found", type="integer", example=2),
     *             @OA\Property(property="total_banned", type="integer", example=1),
     *             @OA\Property(property="total_not_found", type="integer", example=0),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="found",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=25),
     *                         @OA\Property(property="name", type="string", example="pikachu"),
     *                         @OA\Property(property="height", type="integer", example=4),
     *                         @OA\Property(property="weight", type="integer", example=60),
     *                         @OA\Property(property="types", type="array", @OA\Items(type="string", example="electric"))
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="banned",
     *                     type="array",
     *                     @OA\Items(type="string", example="mewtwo")
     *                 ),
     *                 @OA\Property(
     *                     property="not_found",
     *                     type="array",
     *                     @OA\Items(type="string", example="fakemon")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function index(PokemonInfoRequest $request): JsonResponse
    {
        $pokemonNames = $request->input('pokemons');
        $result = $this->service->getPokemonsInfo($pokemonNames);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}
