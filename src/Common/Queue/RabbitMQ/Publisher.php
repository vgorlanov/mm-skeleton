<?php

declare(strict_types=1);

namespace Common\Queue\RabbitMQ;

use Common\Queue\RabbitMQ\Exchanges\Exchange;
use Common\Queue\RabbitMQ\Infrastructure\Connection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

final class Publisher
{
    private AMQPChannel $channel;

    /**
     * @throws \Exception
     */
    public function __construct(
        private ?AMQPStreamConnection $connection = null,
    ) {
        $this->connection = $this->connection ?: Connection::instance();
        $this->channel = $this->connection->channel();
    }

    /**
     * @param AMQPMessage $message
     * @param Exchange $exchange
     * @param Keys|null $routing_key
     * @return void
     */
    public function message(AMQPMessage $message, Exchange $exchange, ?Keys $routing_key = null): void
    {
        $this->channel->basic_publish(
            msg: $message,
            exchange: $exchange->exchange(),
            routing_key: $routing_key ? $routing_key->value : '',
        );
    }
}
