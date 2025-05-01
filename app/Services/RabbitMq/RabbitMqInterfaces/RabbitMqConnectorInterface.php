<?php

declare(strict_types=1);

namespace App\Services\RabbitMq\RabbitMqInterfaces;

interface RabbitMqConnectorInterface extends
    RabbitMqConsumerInterface,
    RabbitMqPublisherInterface
{
}
