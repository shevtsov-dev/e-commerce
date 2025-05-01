<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Traits\SuccessMessageTrait;
use App\Models\User;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    use SuccessMessageTrait;

    /**
     * @return string
     */
    protected function entityKey(): string
    {
        return 'user';
    }

    /**
     * @const PAGE_LIMIT
     */
    private const PAGE_LIMIT = 10;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param UserService             $userService
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UserService             $userService,
    ) {
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => $this->userRepository
                ->paginate(self::PAGE_LIMIT),
        ]);
    }

    /**
     * @param int $userId
     *
     * @return View
     */
    public function show(int $userId): View
    {
        return view('admin.users.show', [
            'user' => $this->findOrFail($userId),
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * @param UserRequest $request
     *
     * @return RedirectResponse
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $this->userRepository
            ->create($request->validated());

        return redirect(route('admin.users.index'))
            ->with('success', $this->successMessage('create'));
    }

    /**
     * @param int $userId
     *
     * @return View
     */
    public function edit(int $userId): View
    {
        return view('admin.users.edit', [
            'user' => $this->findOrFail($userId),
        ]);
    }

    /**
     * @param UserRequest $request
     * @param int         $userId
     *
     * @return RedirectResponse
     */
    public function update(UserRequest $request, int $userId): RedirectResponse
    {
        $this->userService
            ->updateUser($request, $userId);

        return redirect(route('admin.users.index'))
            ->with('success', $this->successMessage('update'));
    }

    /**
     * @param int $userId
     *
     * @return RedirectResponse
     */
    public function destroy(int $userId): RedirectResponse
    {
        $this->userService
            ->deleteUser($userId);

        return redirect(route('admin.users.index'))
            ->with('success', $this->successMessage('delete'));
    }

    /**
     * @param int $userId
     *
     * @return User
     */
    private function findOrFail(int $userId): User
    {
        return $this->userRepository->findById($userId);
    }
}
