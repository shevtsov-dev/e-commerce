<?php

declare(strict_types=1);

namespace App\Services\RabbitMq\RabbitMqInterfaces;

use PhpAmqpLib\Channel\AMQPChannel;

interface RabbitMqConsumerInterface
{
    /**
     * @return AMQPChannel
     */
    public function getChannel(): AMQPChannel;

    /**
     * @return void
     */
    public function close(): void;
}
