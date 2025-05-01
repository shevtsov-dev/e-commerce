<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

final class HomeController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('home');
    }
}
