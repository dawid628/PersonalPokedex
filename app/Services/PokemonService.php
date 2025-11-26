<?php

namespace App\Services;

use App\Models\Pokemon;
use App\Repositories\PokemonRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;


readonly class PokemonService
{
    public function __construct(
        private PokemonRepository $repository,
        private PokeApiService $pokeApiService
    ) {}

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getByName(string $name): ?Pokemon
    {
        $name = strtolower($name);
        $pokemon = $this->repository->findByName($name);

        if (!$pokemon) {
            return null;
        }

        $pokemon->is_custom = true;

        return $pokemon;
    }

    /**
     * @param string $name
     * @return Pokemon
     * @throws Exception
     */
    public function create(string $name): Pokemon
    {
        $name = strtolower($name);

        if ($this->repository->exists($name)) {
            throw new Exception('Pokemon with this name already exists in pokemons');
        }

        $pokeApiPokemon = $this->pokeApiService->getOne($name);
        if ($pokeApiPokemon) {
            throw new Exception('Pokemon with this name already exists in PokeAPI');
        }

        return $this->repository->create($name);
    }

    /**
     * @param string $name
     * @return void
     * @throws Exception
     */
    public function delete(string $name): void
    {
        $name = strtolower($name);
        $pokemon = $this->repository->findByName($name);

        if (!$pokemon) {
            throw new Exception('Pokemon not found');
        }

        $this->repository->delete($pokemon);
    }
}
