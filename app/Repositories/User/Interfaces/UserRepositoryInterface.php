<?php

declare(strict_types=1);

namespace App\Repositories\User\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * @param int|null $limit
     *
     * @return Collection<int, User>
     */
    public function getAll(?int $limit = null): Collection;

    /**
     * @param int $id
     *
     * @return User
     */
    public function findById(int $id): User;

    /**
     * @param array<string, mixed> $data
     *
     * @return User
     */
    public function create(array $data): User;

    /**
     * @param int                  $id
     * @param array<string, mixed> $data
     *
     * @return User
     */
    public function update(int $id, array $data): User;

    /**
     * @param int $id
     *
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @param int $limitPerPage
     *
     * @return LengthAwarePaginator<int, User>
     */
    public function paginate(int $limitPerPage): LengthAwarePaginator;

    /**
     * @param array<string, string> $credentials
     *
     * @return bool
     */
    public function attemptLogin(array $credentials): bool;

    /**
     * @return void
     */
    public function logoutUser(): void;

    /**
     * @return Authenticatable|null
     */
    public function getAuthenticatedUser(): ?Authenticatable;
}
