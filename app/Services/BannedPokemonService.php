<?php

namespace App\Services;

use App\Repositories\BannedPokemonRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;


readonly class BannedPokemonService
{
    public function __construct(
        private BannedPokemonRepository $repository
    )
    {
    }

    /**
     * @return Collection
     */
    public function getAllBanned(): Collection
    {
        return $this->repository->getAll();
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getBannedByName(string $name): ?array
    {
        $name = strtolower($name);
        $banned = $this->repository->findByName($name);

        if (!$banned) {
            return null;
        }

        return [
            'banned' => $banned
        ];
    }

    /**
     * @param string $name
     * @return array
     * @throws Exception
     */
    public function banPokemon(string $name): array
    {
        $name = strtolower($name);

        if ($this->repository->exists($name)) {
            throw new Exception('Pokemon is already banned');
        }

        $banned = $this->repository->create($name);

        return [
            'banned' => $banned
        ];
    }

    /**
     * @param string $name
     * @return void
     * @throws Exception
     */
    public function unbanPokemon(string $name): void
    {
        $name = strtolower($name);
        $banned = $this->repository->findByName($name);

        if (!$banned) {
            throw new Exception('Pokemon not found in banned list');
        }

        $this->repository->delete($banned);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isBanned(string $name): bool
    {
        $name = strtolower($name);
        return $this->repository->exists($name);
    }
}
