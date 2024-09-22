<?php

declare(strict_types=1);

namespace Common\Queue\RabbitMQ\Dto;

use Common\Queue\RabbitMQ\Keys;
use Common\Queue\RabbitMQ\Queues\Queue;

/**
 * DTO
 */
final readonly class Bind
{
    public function __construct(
        public Queue $queue,
        public ?Keys $key = null,
    ) {}
}
