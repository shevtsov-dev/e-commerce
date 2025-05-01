<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param User $model
     */
    public function __construct(
        protected User $model
    ) {
    }

    /**
     * @param int $limitPerPage
     *
     * @return LengthAwarePaginator<int, User>
     */
    public function paginate(int $limitPerPage): LengthAwarePaginator
    {
        return $this->query()
            ->with('role')
            ->paginate($limitPerPage);
    }

    /**
     * @param array<string, string> $credentials
     *
     * @return bool
     */
    public function attemptLogin(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    /**
     * @return void
     */
    public function logoutUser(): void
    {
        Auth::logout();
    }

    /**
     * @return Authenticatable|null
     */
    public function getAuthenticatedUser(): ?Authenticatable
    {
        return Auth::user();
    }

    /**
     * @param int|null $limit
     *
     * @return Collection<int, User>
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
     * @return User
     */
    public function findById(int $id): User
    {
        return $this->query()
            ->findOrFail($id);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return User
     */
    public function create(array $data): User
    {
        return $this->query()
            ->create($data);
    }

    /**
     * @param int                  $id
     * @param array<string, mixed> $data
     *
     * @return User
     */
    public function update(int $id, array $data): User
    {
        $user = $this->findById($id);
        $user->update($data);

        return $user;
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
     * @return Builder<User>
     */
    private function query(): Builder
    {
        return $this->model->newQuery();
    }
}
