<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeSubscriberMail;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscribeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $email = $validated['email'];

        if (Subscriber::query()->where('email', $email)->exists()) {
            return back()->with('error', 'You are already subscribed!');
        }

        Subscriber::query()->create(['email' => $email]);

        Mail::to($email)->send(new WelcomeSubscriberMail());

        return back()->with('success', 'Thanks for subscribing!');
    }
}
