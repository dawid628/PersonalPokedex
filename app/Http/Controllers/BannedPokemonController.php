<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBannedPokemonRequest;
use App\Services\BannedPokemonService;
use Illuminate\Http\JsonResponse;


/**
 * @OA\Tag(
 *     name="Banned Pokemons",
 *     description="API endpoints for managing banned pokemons"
 * )
 */
class BannedPokemonController extends Controller
{
    public function __construct(
        private readonly BannedPokemonService $service
    ) {}

    /**
     * @OA\Get(
     *     path="/api/banned",
     *     tags={"Banned Pokemons"},
     *     summary="Get all banned pokemons",
     *     description="Returns list of all banned pokemons",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="total", type="integer", example=2),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="pikachu")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $banned = $this->service->getAllBanned();

        return response()->json([
            'success' => true,
            'total' => $banned->count(),
            'data' => $banned
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/banned",
     *     tags={"Banned Pokemons"},
     *     summary="Add pokemon to banned list",
     *     description="Adds a pokemon to the banned list",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="pikachu")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pokemon banned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pokemon banned successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="pikachu")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Pokemon already banned"
     *     )
     * )
     */
    public function store(StoreBannedPokemonRequest $request): JsonResponse
    {
        try {
            $data = $this->service->banPokemon($request->input('name'));

            return response()->json([
                'success' => true,
                'message' => 'Pokemon banned successfully',
                'data' => $data
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 409);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/banned/{name}",
     *     tags={"Banned Pokemons"},
     *     summary="Check if pokemon is banned",
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Pokemon is banned"),
     *     @OA\Response(response=404, description="Pokemon is not banned")
     * )
     */
    public function show(string $name): JsonResponse
    {
        $banned = $this->service->getBannedByName($name);

        if (!$banned) {
            return response()->json([
                'success' => false,
                'message' => 'Pokemon is not banned',
                'is_banned' => false
            ], 404);
        }

        return response()->json([
            'success' => true,
            'is_banned' => true,
            'data' => $banned
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/banned/{name}",
     *     tags={"Banned Pokemons"},
     *     summary="Remove pokemon from banned list",
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Pokemon removed successfully"),
     *     @OA\Response(response=404, description="Pokemon not found")
     * )
     */
    public function destroy(string $name): JsonResponse
    {
        try {
            $this->service->unbanPokemon($name);

            return response()->json([
                'success' => true,
                'message' => 'Pokemon removed from banned list'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
