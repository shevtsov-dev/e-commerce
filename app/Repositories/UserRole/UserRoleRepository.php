<?php

declare(strict_types=1);

namespace App\Repositories\UserRole;

use App\Models\Role;
use App\Repositories\UserRole\Interfaces\UserRoleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UserRoleRepository implements UserRoleRepositoryInterface
{
    /**
     * @param Role $model
     */
    public function __construct(
        protected Role $model
    ) {
    }

    /**
     * @param int|null $limit
     *
     * @return Collection<int, Role>
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
     * @return Role
     */
    public function findById(int $id): Role
    {
        return $this->query()
            ->findOrFail($id);
    }

    /**
     * @return Builder<Role>
     */
    private function query(): Builder
    {
        return $this->model->newQuery();
    }
}
