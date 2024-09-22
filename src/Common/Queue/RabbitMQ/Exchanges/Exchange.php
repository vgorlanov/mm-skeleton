<?php

declare(strict_types=1);

namespace Common\Queue\RabbitMQ\Exchanges;

use Common\Queue\RabbitMQ\Dto\Bind;
use Common\Queue\RabbitMQ\Infrastructure\Connection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Wire\AMQPTable;

/**
 * @phpstan-type ExchangeT array{exchange: string, type: string, passive: bool, durable: bool, auto_delete: bool, internal: bool, nowait: bool, arguments: array<mixed>|AMQPTable, ticket: int|null}
 */
abstract class Exchange
{
    private AMQPChannel $channel;

    public function __construct(
        private ?AMQPStreamConnection $connection = null,
    ) {
        $this->connection = $this->connection ?: Connection::instance();
        $this->channel = $this->connection->channel();
    }

    abstract public function exchange(): string;

    abstract public function type(): string;

    abstract public function passive(): bool;

    abstract public function durable(): bool;

    abstract public function autoDelete(): bool;

    abstract public function internal(): bool;

    abstract public function nowait(): bool;

    /**
     * @return Bind[]
     */
    abstract public function binds(): array;

    /**
     * @return array<mixed>|AMQPTable
     */
    abstract public function arguments();

    abstract public function ticket(): int|null;

    public function make(): void
    {
        $this->channel->exchange_declare(...$this->toArray());
    }

    /**
     * @return ExchangeT
     */
    public function toArray(): array
    {
        return [
            'exchange'    => $this->exchange(),
            'type'        => $this->type(),
            'passive'     => $this->passive(),
            'durable'     => $this->durable(),
            'auto_delete' => $this->autoDelete(),
            'internal'    => $this->internal(),
            'nowait'      => $this->nowait(),
            'arguments'   => $this->arguments(),
            'ticket'      => $this->ticket(),
        ];
    }
}
