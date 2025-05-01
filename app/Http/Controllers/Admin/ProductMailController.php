<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductCsvExporterToQueue;
use Illuminate\Http\RedirectResponse;
use Throwable;

class ProductMailController extends Controller
{
    /**
     * @param ProductCsvExporterToQueue $productCsvExporterToQueue
     */
    public function __construct(
        private readonly ProductCsvExporterToQueue $productCsvExporterToQueue,
    ) {
    }

    /**
     * @return RedirectResponse
     */
    public function export(): RedirectResponse
    {
        try {
            $this->productCsvExporterToQueue->exportProductsToQueue();

            return back()->with('success', safe_trans('messages.export_success'));
        } catch (Throwable  $exception) {
            return back()->with('error', safe_trans('messages.export_failed', [
                'error' => $exception->getMessage(),
            ]));
        }
    }
}
