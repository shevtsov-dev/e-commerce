<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CurrencyRateService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

final class CurrencyController extends Controller
{
    /**
     * @param CurrencyRateService $currencyRateService
     */
    public function __construct(
        protected CurrencyRateService $currencyRateService,
    ) {
    }

    /**
     * @return RedirectResponse
     */
    public function updateRates(): RedirectResponse
    {
        try {
            $this->currencyRateService->updateRates();

            return back()->with('success', safe_trans('messages.currency_updated_success'));
        } catch (Exception $e) {
            logger()->error(safe_trans('messages.currency_update_exception_log', [
                'message' => $e->getMessage(),
            ]));

            return back()->with('error', safe_trans('messages.currency_updated_fail'));
        }
    }

    /**
     * @return View
     */
    public function showRates(): View
    {
        return view('admin.currency-rates', [
            'rates' => $this->currencyRateService->getRates(),
        ]);
    }
}
