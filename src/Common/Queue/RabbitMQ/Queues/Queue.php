<?php

declare(strict_types=1);

namespace Common\Queue\RabbitMQ\Queues;

use Common\Queue\RabbitMQ\Infrastructure\Connection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Wire\AMQPTable;

/**
 * @phpstan-type QueueT array{queue: string, passive: bool, durable: bool, exclusive: bool, auto_delete: bool, nowait: bool, arguments: array<mixed>|AMQPTable, ticket: int|null}
 */
abstract class Queue
{
    private AMQPChannel $channel;

    public function __construct(
        private ?AMQPStreamConnection $connection = null,
    ) {
        $this->connection = $this->connection ?: Connection::instance();
        $this->channel = $this->connection->channel();
    }

    abstract public function queue(): string;

    abstract public function passive(): bool;

    abstract public function durable(): bool;

    abstract public function exclusive(): bool;

    abstract public function autoDelete(): bool;

    abstract public function nowait(): bool;

    /**
     * @return array<mixed>|AMQPTable
     */
    abstract public function arguments(): AMQPTable|array;

    abstract public function ticket(): int|null;

    public function make(): void
    {
        $this->channel->queue_declare(...$this->toArray());
    }

    /**
     * @return QueueT
     */
    public function toArray(): array
    {
        return [
            'queue'       => $this->queue(),
            'passive'     => $this->passive(),
            'durable'     => $this->durable(),
            'exclusive'   => $this->exclusive(),
            'auto_delete' => $this->autoDelete(),
            'nowait'      => $this->nowait(),
            'arguments'   => $this->arguments(),
            'ticket'      => $this->ticket(),
        ];
    }
}
