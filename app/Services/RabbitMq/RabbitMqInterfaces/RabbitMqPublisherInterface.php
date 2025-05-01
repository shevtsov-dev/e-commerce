<?php

declare(strict_types=1);

namespace App\Services\RabbitMq\RabbitMqInterfaces;

interface RabbitMqPublisherInterface
{
    /**
     * @param string $queue
     * @param string $message
     *
     * @return void
     */
    public function publish(string $queue, string $message): void;
}
