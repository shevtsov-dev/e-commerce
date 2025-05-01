<?php

declare(strict_types=1);

namespace App\Repositories\Product\Interfaces;

use App\Models\Producer;
use Illuminate\Database\Eloquent\Collection;

interface ProducerRepositoryInterface
{
    /**
     * @param int|null $limit
     *
     * @return Collection<int, Producer>
     */
    public function getAll(?int $limit = null): Collection;

    /**
     * @param int $producerId
     *
     * @return Producer
     */
    public function findById(int $producerId): Producer;

    /**
     * @param int $producerId
     *
     * @return Producer
     */
    public function findProducerWithProducts(int $producerId): Producer;

    /**
     * @param array<string, mixed> $data
     *
     * @return Producer
     */
    public function create(array $data): Producer;

    /**
     * @param int                  $id
     * @param array<string, mixed> $data
     *
     * @return Producer
     */
    public function update(int $id, array $data): Producer;

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool;
}
