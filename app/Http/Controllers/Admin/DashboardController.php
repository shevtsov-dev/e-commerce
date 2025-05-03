<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UserService             $userService,
    ) {
    }

    public function index(): View
    {
        $users = $this->userRepository->paginate(10);

        return view('admin.dashboard', compact('users'));
    }
}
