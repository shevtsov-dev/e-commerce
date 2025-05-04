<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * @var User|null $user
         */
        $user = Auth::user();

        if ($user && $user->role_id !== 1) {
            return response()->view('errors.404');
        }

        return $next($request);
    }
}
