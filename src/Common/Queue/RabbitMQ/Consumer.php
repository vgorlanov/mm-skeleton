<?php

declare(strict_types=1);

namespace Common\Queue\RabbitMQ;

use Common\Queue\Message;
use Common\Queue\RabbitMQ\Infrastructure\Connection;
use Common\Queue\RabbitMQ\Queues\Queue;
use Common\Uuid\Uuid;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

final class Consumer
{
    private AMQPChannel $channel;

    /**
     * @var callable(Message): void $callback
     */
    private $callback;

    public function __construct(
        private ?AMQPStreamConnection $connection = null,
    ) {
        $this->connection = $this->connection ?: Connection::instance();
        $this->channel = $this->connection->channel();
    }

    /**
     * @param callable(Message): void $callback
     * @param Queue $queue
     * @return void
     */
    public function consume(callable $callback, Queue $queue): void
    {
        $this->callback = $callback;

        $this->channel->basic_consume(
            queue: $queue->queue(),
            callback: [$this, 'decorate'],
        );

        while ($this->channel->is_open()) {
            $this->channel->wait();
        }
    }

    /**
     * @throws \Exception
     */
    public function decorate(AMQPMessage $msg): void
    {
        $body = json_decode($msg->body);

        $message = new Message(
            name: $body->name,
            body: is_object($body->body) ? (array) $body->body : $body->body,
            uuid: new Uuid($body->uuid),
            date: new \DateTimeImmutable($body->date->date),
        );

        call_user_func($this->callback, $message);
    }

}
