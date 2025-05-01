<?php

declare(strict_types=1);

namespace App\Repositories\UserRole\Interfaces;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface UserRoleRepositoryInterface
{
    /**
     * @param int|null $limit
     *
     * @return Collection<int, Role>
     */
    public function getAll(?int $limit = null): Collection;

    /**
     * @param int $id
     *
     * @return Role
     */
    public function findById(int $id): Role;
}
