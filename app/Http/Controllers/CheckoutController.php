<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

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
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $cart = session('cart', []);
        $total = collect($cart)->reduce(fn($carry, $item) => $carry + $item['price'] * $item['quantity'], 0);

        $orderText = "🛒 Новый заказ:\n\n";
        foreach ($cart as $item) {
            $orderText .= "🔹 {$item['name']} — {$item['quantity']} x {$item['price']} BYN\n";
        }
        $orderText .= "\n💰 Итоговая сумма: $total BYN\n";
        $orderText .= "📧 Email покупателя: $email";

        Mail::raw($orderText, function ($message) use ($email) {
            $message->to($email)
                ->subject('Ваш заказ принят');
        });

        $response = Http::post("https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN') . "/sendMessage", [
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'text' => $orderText,
        ]);

        session()->forget('cart');

        return redirect()->route('checkout.thankyou')->with('success', 'Спасибо за заказ! Подробности на почте.');
    }
}
