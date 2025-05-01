<?php

declare(strict_types=1);

namespace App\Repositories\Product;

use App\Models\Producer;
use App\Repositories\Product\Interfaces\ProducerRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProducerRepository implements ProducerRepositoryInterface
{
    /**
     * @param Producer $model
     */
    public function __construct(
        protected Producer $model,
    ) {
    }

    /**
     * @param int|null $limit
     *
     * @return Collection<int, Producer>
     */
    public function getAll(?int $limit = null): Collection
    {
        $query = $this->query();

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * @param int $limitPerPage
     *
     * @return LengthAwarePaginator<int, Producer>
     */
    public function paginate(int $limitPerPage): LengthAwarePaginator
    {
        return $this->query()
            ->paginate($limitPerPage);
    }

    /**
     * @param int $producerId
     *
     * @return Producer
     */
    public function findById(int $producerId): Producer
    {
        return $this->query()
            ->findOrFail($producerId);
    }

    /**
     * @param int $producerId
     *
     * @return Producer
     */
    public function findProducerWithProducts(int $producerId): Producer
    {
        return $this->query()
            ->with('products')
            ->findOrFail($producerId);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return Producer
     */
    public function create(array $data): Producer
    {
        return $this->query()->create($data);
    }

    /**
     * @param int                  $id
     * @param array<string, mixed> $data
     *
     * @return Producer
     */
    public function update(int $id, array $data): Producer
    {
        $producer = $this->findById($id);
        $producer->update($data);

        return $producer;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        return (bool) $this->query()
            ->find($id)
            ?->delete();
    }

    /**
     * @return Builder<Producer>
     */
    private function query(): Builder
    {
        return $this->model->newQuery();
    }
}
