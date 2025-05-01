<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Export\Interfaces\ExportProcessorInterface;
use App\Services\RabbitMq\RabbitMqInterfaces\RabbitMqConnectorInterface;
use Exception;
use Illuminate\Console\Command;
use PhpAmqpLib\Message\AMQPMessage;
use Throwable;

class ListenQueue extends Command
{
    /**
     * @var string
     */
    protected $signature = 'queue:listen';

    /**
     * @var string
     */
    protected $description = 'Listen to the export queue';

    /**
     * @const LISTEN_QUEUE_NAME
     */
    private const LISTEN_QUEUE_NAME = 'export_queue';

    /**
     * @param ExportProcessorInterface   $exportProcessor
     * @param RabbitMqConnectorInterface $rabbitMqConnector
     */
    public function __construct(
        protected ExportProcessorInterface $exportProcessor,
        protected RabbitMqConnectorInterface $rabbitMqConnector,
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        try {
            $channel = $this->rabbitMqConnector->getChannel();

            $this->info(safe_trans('messages.queue_listening', ['queue' => self::LISTEN_QUEUE_NAME]));

            $channel->basic_consume(
                self::LISTEN_QUEUE_NAME,
                '',
                false,
                true,
                false,
                false,
                function (AMQPMessage $msg) {
                    $this->processMessage($msg->getBody());
                }
            );

            while ($channel->is_consuming()) {
                $channel->wait();
            }

        } catch (Exception $e) {
            $this->error(safe_trans('messages.rabbitmq_handle_error', ['message' => $e->getMessage()]));
        } finally {
            $this->cleanup();
        }
    }

    /**
     * @param string $csvData
     *
     * @return void
     */
    private function processMessage(string $csvData): void
    {
        try {
            $start = microtime(true);

            $this->exportProcessor->handle($csvData);

            $duration = round(microtime(true) - $start, 4);
            $this->info(safe_trans('messages.processing_duration', ['time' => $duration]));
        } catch (Throwable $e) {
            $this->error(safe_trans('messages.rabbitmq_process_error', ['message' => $e->getMessage()]));
        }
    }

    /**
     * @return void
     */
    private function cleanup(): void
    {
        try {
            $this->rabbitMqConnector->close();
        } catch (Exception $e) {
            $this->error(safe_trans('messages.rabbitmq_cleanup_error', ['message' => $e->getMessage()]));
        }
    }
}
