<?php

namespace App\Repositories;

use App\Models\BannedPokemon;
use Illuminate\Database\Eloquent\Collection;


class BannedPokemonRepository
{
    private BannedPokemon $model;

    public function __construct(BannedPokemon $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param string $name
     * @return BannedPokemon|null
     */
    public function findByName(string $name): ?BannedPokemon
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function exists(string $name): bool
    {
        return $this->model->where('name', $name)->exists();
    }

    /**
     * @param string $name
     * @return BannedPokemon
     */
    public function create(string $name): BannedPokemon
    {
        return $this->model->create(['name' => $name]);
    }

    /**
     * @param BannedPokemon $bannedPokemon
     * @return bool
     */
    public function delete(BannedPokemon $bannedPokemon): bool
    {
        return $bannedPokemon->delete();
    }
}
