<?php

declare(strict_types=1);

namespace App\Services;

use App\Formatters\Interfaces\DataFormatterInterface;
use App\Repositories\Product\Interfaces\ProductRepositoryInterface;
use App\Services\RabbitMq\RabbitMqInterfaces\RabbitMqConnectorInterface;
use Exception;
use Psr\Log\LoggerInterface;

class ProductCsvExporterToQueue
{
    /**
     * @const QUEUE_NAME
     */
    private const QUEUE_NAME = 'export_queue';

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param RabbitMqConnectorInterface $rabbitMqConnector
     * @param DataFormatterInterface     $productCsvFormatter
     * @param LoggerInterface            $logger,
 */
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly RabbitMqConnectorInterface $rabbitMqConnector,
        private readonly DataFormatterInterface $productCsvFormatter,
        protected LoggerInterface $logger,
    ) {
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function exportProductsToQueue(): void
    {
        try {
            $products = $this->productRepository->getAll();
            $csvData  = $this->productCsvFormatter->format($products);
            $this->rabbitMqConnector->publish(self::QUEUE_NAME, $csvData);
        } catch (Exception $e) {
            $this->logger->error(safe_trans('messages.export_failed' . $e->getMessage()));
            throw new Exception(safe_trans('messages.export_failed'));
        }
    }
}
