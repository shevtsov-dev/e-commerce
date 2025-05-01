<?php

declare(strict_types=1);

namespace App\Repositories\Service\Interfaces;

use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ServiceRepositoryInterface
{
    /**
     * @param int|null $limit
     *
     * @return Collection<int, Service>
     */
    public function getAll(?int $limit = null): Collection;

    /**
     * @param int $id
     *
     * @return Service
     */
    public function findById(int $id): Service;

    /**
     * @param array<string, mixed> $data
     *
     * @return Service
     */
    public function create(array $data): Service;

    /**
     * @param int                  $id
     * @param array<string, mixed> $data
     *
     * @return Service
     */
    public function update(int $id, array $data): Service;

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @param int $limitPerPage
     *
     * @return LengthAwarePaginator<int, Service>
     */
    public function paginate(int $limitPerPage): LengthAwarePaginator;
}
