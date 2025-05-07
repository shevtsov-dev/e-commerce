<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use Psr\Log\LoggerInterface;

class UserService
{
    /**
     * @param UserRepositoryInterface $userRepository
     * @param LoggerInterface         $logger
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * @param UserRequest $request
     * @param int         $userId
     *
     * @return User
     */
    public function updateUser(UserRequest $request, int $userId): User
    {
        $user = $this->userRepository
            ->findById($userId);

        $data = $request->validated();
        unset($data['email']);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);
        $this->logger->info(safe_trans('messages.updated_success'. $user->id));

        return $user;
    }

    /**
     * @param int $userId
     *
     * @return void
     */
    public function deleteUser(int $userId): void
    {
        $user = $this->userRepository
            ->findById($userId);
        $user->delete();
        $this->logger->info(safe_trans('messages.deleted_success'. $user->id));
    }
}
