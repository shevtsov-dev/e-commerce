<?php

declare(strict_types=1);

namespace App\Repositories\Service;

use App\Models\Service;
use App\Repositories\Service\Interfaces\ServiceRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceRepository implements ServiceRepositoryInterface
{
    /**
     * @param Service $model
     */
    public function __construct(
        protected Service $model,
    ) {
    }

    /**
     * @param int|null $limit
     *
     * @return Collection<int, Service>
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
     * @param int $id
     *
     * @return Service
     */
    public function findById(int $id): Service
    {
        return $this->query()
            ->findOrFail($id);
    }

    /**
     * @param int $limitPerPage
     *
     * @return LengthAwarePaginator<int, Service>
     */
    public function paginate(int $limitPerPage): LengthAwarePaginator
    {
        return $this->query()
            ->paginate($limitPerPage);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return Service
     */
    public function create(array $data): Service
    {
        return $this->query()
            ->create($data);
    }

    /**
     * @param int                  $id
     * @param array<string, mixed> $data
     *
     * @return Service
     */
    public function update(int $id, array $data): Service
    {
        $service = $this->findById($id);
        $service->update($data);

        return $service;
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
     * @return Builder<Service>
     */
    private function query(): Builder
    {
        return $this->model->newQuery();
    }
}
