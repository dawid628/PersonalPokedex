<?php

namespace App\Repositories;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Collection;


readonly class PokemonRepository
{
    public function __construct(
        private Pokemon $model
    ) {}

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param string $name
     * @return Pokemon|null
     */
    public function findByName(string $name): ?Pokemon
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
     * @return Pokemon
     */
    public function create(string $name): Pokemon
    {
        return $this->model->create(['name' => $name]);
    }

    /**
     * @param Pokemon $pokemon
     * @return bool
     */
    public function delete(Pokemon $pokemon): bool
    {
        return $pokemon->delete();
    }
}
