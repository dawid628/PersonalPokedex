<?php

namespace App\Services;

readonly class PokemonInfoService
{
    public function __construct(
        private PokeApiService       $pokeApiService,
        private BannedPokemonService $bannedPokemonService,
        private PokemonService       $pokemonService
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

            $customPokemon = $this->pokemonService->getByName($name);
            if ($customPokemon) {
                $pokemons[$name] = $customPokemon;
                continue;
            }

            $details = $this->pokeApiService->getOne($name);

            if ($details) {
                $details['is_custom'] = false;
                $pokemons[$name] = $details;
            }
        }

        return $pokemons;
    }
}
