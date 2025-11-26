<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePokemonRequest;
use App\Services\PokemonService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Pokemons",
 *     description="API endpoints for managing pokemons"
 * )
 */
class PokemonController extends Controller
{
    public function __construct(
        private readonly PokemonService $service
    ) {}

    /**
     * @OA\Get(
     *     path="/api/pokemons",
     *     tags={"Pokemons"},
     *     summary="Get all custom pokemons",
     *     description="Returns list of all custom pokemons",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="total", type="integer", example=5),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="mypokemon"),
     *                     @OA\Property(property="created_at", type="string"),
     *                     @OA\Property(property="updated_at", type="string")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $customPokemons = $this->service->getAll();

        return response()->json([
            'success' => true,
            'data' => $customPokemons
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/pokemons",
     *     tags={"Pokemons"},
     *     summary="Create custom pokemon",
     *     description="Creates a new custom pokemon. Requires API key authentication.",
     *     security={{"ApiKeyAuth": {}}},
     *     @OA\Parameter(
     *         name="X-SUPER-SECRET-KEY",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="mypokemon", description="Pokemon name (lowercase, letters, numbers and hyphens only)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pokemon created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Custom pokemon created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="mypokemon"),
     *                 @OA\Property(property="is_custom", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=409, description="Pokemon already exists"),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(StorePokemonRequest $request): JsonResponse
    {
        try {
            $data = $this->service->create($request->input('name'));

            return response()->json([
                'success' => true,
                'message' => 'Pokemon created successfully',
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
     *     path="/api/pokemons/{name}",
     *     tags={"Pokemons"},
     *     summary="Get custom pokemon by name",
     *     description="Get details of a custom pokemon. Requires API key authentication.",
     *     security={{"ApiKeyAuth": {}}},
     *     @OA\Parameter(name="X-SUPER-SECRET-KEY", in="header", required=true, @OA\Schema(type="string")),
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="mypokemon"),
     *                 @OA\Property(property="is_custom", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(string $name): JsonResponse
    {
        $pokemon = $this->service->getByName($name);

        if (!$pokemon) {
            return response()->json([
                'success' => false,
                'message' => 'Pokemon not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pokemon
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/pokemons/{name}",
     *     tags={"Pokemons"},
     *     summary="Delete custom pokemon",
     *     description="Delete a custom pokemon. Requires API key authentication.",
     *     security={{"ApiKeyAuth": {}}},
     *     @OA\Parameter(name="X-SUPER-SECRET-KEY", in="header", required=true, @OA\Schema(type="string")),
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Custom pokemon deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy(string $name): JsonResponse
    {
        try {
            $this->service->delete($name);

            return response()->json([
                'success' => true,
                'message' => 'Pokemon deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
