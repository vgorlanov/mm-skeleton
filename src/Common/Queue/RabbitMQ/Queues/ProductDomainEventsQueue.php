<?php

declare(strict_types=1);

namespace Common\Queue\RabbitMQ\Queues;

use PhpAmqpLib\Wire\AMQPTable;

final class ProductDomainEventsQueue extends Queue
{
    private const string QUEUE = 'DomainEvents.Product';
    private const bool PASSIVE = false;
    private const bool DURABLE = false;
    private const bool EXCLUSIVE = false;
    private const bool AUTO_DELETE = false;
    private const bool NOWAIT = false;
    /**
     * @var array<mixed>|AMQPTable
     */
    private const array|AMQPTable ARGUMENTS = [];
    private const int|null TICKET = null;

    public function queue(): string
    {
        return self::QUEUE;
    }

    public function passive(): bool
    {
        return self::PASSIVE;
    }

    public function durable(): bool
    {
        return self::DURABLE;
    }

    public function exclusive(): bool
    {
        return self::EXCLUSIVE;
    }

    public function autoDelete(): bool
    {
        return self::AUTO_DELETE;
    }

    public function nowait(): bool
    {
        return self::NOWAIT;
    }

    public function arguments(): AMQPTable|array
    {
        return self::ARGUMENTS;
    }

    public function ticket(): int|null
    {
        return self::TICKET;
    }
}
