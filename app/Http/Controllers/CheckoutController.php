<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory
    {
        $cart = session('cart', []);
        $total = collect($cart)->reduce(fn($carry, $item) => $carry + $item['price'] * $item['quantity'], 0);

        return view('checkout.index', compact('cart', 'total'));
    }

    public function process(Request $request): RedirectResponse
    {
        $cart = session('cart', []);
        $total = collect($cart)->reduce(fn($carry, $item) => $carry + $item['price'] * $item['quantity'], 0);

        // Тут будет логика оплаты, пока просто:
        // 1. Очистим корзину
        session()->forget('cart');

        // 2. Перенаправим на спасибо
        return redirect()->route('checkout.thankyou')->with('success', 'Payment successful!');
    }
}
