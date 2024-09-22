<?php

declare(strict_types=1);

namespace Common\Queue\RabbitMQ\Infrastructure;

use Common\Queue\RabbitMQ\Exchanges\Exchange;
use Common\Queue\RabbitMQ\Keys;
use Common\Queue\RabbitMQ\Queues\Queue;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

final class Binder
{
    private AMQPChannel $channel;

    /**
     * @param AMQPStreamConnection|null $connection
     * @throws \Exception
     */
    public function __construct(
        private ?AMQPStreamConnection $connection = null,
    ) {
        $this->connection = $this->connection ?: Connection::instance();
        $this->channel = $this->connection->channel();
    }

    /**
     * @param Queue $queue
     * @param Exchange $exchange
     * @param Keys|null $key
     * @return void
     */
    public function bind(Queue $queue, Exchange $exchange, ?Keys $key): void
    {
        $this->channel->queue_bind(
            queue: $queue->queue(),
            exchange: $exchange->exchange(),
            routing_key: $key ? $key->value : '',
        );
    }
}
