<?php

namespace App\Services;

use App\Clients\PokeApiClient;
use Illuminate\Support\Facades\Log;


class PokeApiService
{
    public function __construct(private readonly PokeApiClient $client)
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
}
