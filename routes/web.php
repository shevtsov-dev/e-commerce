<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductMailController;
use App\Http\Controllers\Admin\SubscribeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Web\Product\CategoryController;
use App\Http\Controllers\Web\Product\ProducerController;
use App\Http\Controllers\Web\Product\ProductController;
use App\Http\Controllers\Web\Service\ServiceController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\RemovePageOne;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/subscribe', [SubscribeController::class, 'store'])->name('subscribe.store');

Route::fallback(fn () => response()->view('errors.404', [], 404));

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('registration', [AuthController::class, 'showRegistrationForm'])->name('registration');
    Route::post('registration', [AuthController::class, 'registration']);
});

Route::middleware([Authenticate::class])->group(callback: function () {
    Route::middleware(RemovePageOne::class)->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('services', ServiceController::class);
    });

    Route::resource('categories', CategoryController::class);
    Route::resource('producers', ProducerController::class);

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    Route::middleware([CheckRole::class])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('export', [ProductMailController::class, 'export'])->name('export');
        Route::get('currency-rates', [CurrencyController::class, 'showRates'])->name('currency-rates');
        Route::post('currency-rates/update', [CurrencyController::class, 'updateRates']);
    });

        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
        Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/checkout/thankyou', fn() => view('checkout.thankyou'))->name('checkout.thankyou');
});
