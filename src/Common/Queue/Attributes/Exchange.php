<?php

declare(strict_types=1);

namespace Common\Queue\Attributes;

#[\Attribute]
final readonly class Exchange
{
    public function __construct(
        public \Common\Queue\RabbitMQ\Exchanges\Exchange $exchange,
    ) {}
}
