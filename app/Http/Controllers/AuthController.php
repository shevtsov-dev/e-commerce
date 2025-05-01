<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class AuthController extends Controller
{
    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * @param LoginRequest $request
     *
     * @return RedirectResponse
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        if ($this->userRepository->attemptLogin($request->validated())) {
            return redirect(route('home'));
        }

        return back()->withErrors([
            'email' => safe_trans('messages.auth_failed'),
        ]);
    }

    /**
     * @return View
     */
    public function showRegistrationForm(): View
    {
        return view('auth.registration');
    }

    /**
     * @param AuthRequest $request
     *
     * @return RedirectResponse
     */
    public function registration(AuthRequest $request): RedirectResponse
    {
        $this->userRepository
            ->create($request->validated());

        return redirect(route('auth.login'))
            ->with('success', safe_trans('messages.registration_success'));
    }

    /**
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->userRepository->logoutUser();

        return redirect(route('home'));
    }
}
