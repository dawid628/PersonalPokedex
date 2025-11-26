<?php

namespace App\Clients;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;


class PokeApiClient
{
    private ?string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.pokeApi.url', 'https://pokeapi.co/api/v2/');
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Response
     */
    public function getPokemonList(int $limit = 100, int $offset = 0): Response
    {
        return Http::get("$this->apiUrl/pokemon", [
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    /**
     * @param string $name
     * @return Response
     */
    public function getPokemonByName(string $name): Response
    {
        return Http::get("$this->apiUrl/pokemon/$name");
    }
}
