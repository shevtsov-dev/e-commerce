<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\ProductCsvExporterToQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;
use Throwable;

class ExportProductsJob implements ShouldQueue
{
    use Queueable;
    use InteractsWithQueue;
    use SerializesModels;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected LoggerInterface $logger,
    ) {
    }

    public function handle(ProductCsvExporterToQueue $productCsvExporterToQueue): void
    {
        try {
            $productCsvExporterToQueue->exportProductsToQueue();
        } catch (Throwable $e) {
            $this->logger->error(safe_trans('jobs.export_products_failed'), ['error' => $e->getMessage()]);
            $this->fail($e);
        }
    }
}
