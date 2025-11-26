<?php

namespace App\Services;

readonly class PokemonInfoService
{
    public function __construct(
        private PokeApiService $pokeApiService,
        private BannedPokemonService $bannedPokemonService
    ) {}

    /**
     * @param array $pokemonNames
     * @return array
     */
    public function getPokemonsInfo(array $pokemonNames): array
    {
        $pokemons = array();

        foreach ($pokemonNames as $name) {
            $name = strtolower($name);

            if ($this->bannedPokemonService->isBanned($name)) {
                continue;
            }

            $details = $this->pokeApiService->getOne($name);

            if ($details) {
                $pokemons[$name] = $details;
            }
        }

        return $pokemons;
    }
}
