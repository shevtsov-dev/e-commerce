<?php

declare(strict_types=1);

namespace App\Services\RabbitMq;

use App\Services\RabbitMq\RabbitMqInterfaces\RabbitMqConnectorInterface;
use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMqConnector implements RabbitMqConnectorInterface
{
    /**
     * @var AMQPStreamConnection|null
     */
    private ?AMQPStreamConnection $connection = null;

    /**
     * @var AMQPChannel|null
     */
    private ?AMQPChannel $channel = null;

    /**
     * @return AMQPStreamConnection
     *
     * @throws Exception
     */
    public function connect(): AMQPStreamConnection
    {
        if ($this->connection === null) {
            try {
                $this->connection = new AMQPStreamConnection(
                    config('queue.connections.rabbitmq.host'),
                    config('queue.connections.rabbitmq.port'),
                    config('queue.connections.rabbitmq.username'),
                    config('queue.connections.rabbitmq.password')
                );
            } catch (Exception $e) {
                throw $this->exceptionWithTranslation('messages.rabbitmq_connection_failed', $e);
            }
        }

        return $this->connection;
    }

    /**
     * @return AMQPChannel
     *
     * @throws Exception
     */
    public function getChannel(): AMQPChannel
    {
        if ($this->channel === null) {
            $this->channel = $this->connect()->channel();
        }

        return $this->channel;
    }

    /**
     * @param string $queue
     * @param string $message
     *
     * @return void
     *
     * @throws Exception
     */
    public function publish(string $queue, string $message): void
    {
        try {
            $channel = $this->getChannel();

            $channel->queue_declare(
                $queue,
                false,
                true,
                false,
                false,
            );

            $channel->basic_publish(
                new AMQPMessage($message),
                '',
                $queue,
            );
        } catch (Exception $e) {
            throw $this->exceptionWithTranslation('messages.rabbitmq_publish_failed', $e);
        }
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function close(): void
    {
        $this->channel?->close();
        $this->connection?->close();
    }

    /**
     * @param string                          $key
     * @param array<string, string|int|float> $replace
     *
     * @return string
     */
    private function transMessage(string $key, array $replace = []): string
    {
        $translated = __($key, $replace);

        return is_string($translated) ? $translated : 'Translation error';
    }

    /**
     * @param string    $key
     * @param Exception $e
     *
     * @return Exception
     */
    private function exceptionWithTranslation(string $key, Exception $e): Exception
    {
        return new Exception(
            $this->transMessage($key, ['error' => $e->getMessage()]),
            $e->getCode(),
            $e
        );
    }
}
