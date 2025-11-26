<?php

namespace App\Services;

use App\Clients\PokeApiClient;
use Illuminate\Support\Facades\Log;


readonly class PokeApiService
{
    public function __construct(private PokeApiClient $client)
    {}

    /**
     * @param int $limit
     * @param int $offset
     * @return array|mixed|null
     */
    public function getAll(int $limit = 100, int $offset = 0): mixed
    {
        try {
            $response = $this->client->getPokemonList($limit, $offset);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getOne(string $name): ?array
    {
        try {
            $response = $this->client->getPokemonByName($name);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return null;
        }
    }
}
