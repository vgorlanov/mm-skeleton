<?php

declare(strict_types=1);

namespace Common\Queue;

use Common\Queue\Events\Events;
use Common\Queue\Exceptions\QueueEventsException;
use Common\Queue\RabbitMQ\Consumer;
use Common\Queue\RabbitMQ\Publisher;
use PhpAmqpLib\Message\AMQPMessage;

final class Queue
{
    /**
     * @param Message $message
     * @return void
     * @throws Exceptions\QueueEventsException
     * @throws \JsonException
     */
    public function send(Message $message): void
    {
        $event = Events::from($message->getName());

        (new Publisher())->message(
            message: new AMQPMessage($message->toJson()),
            exchange: $event->exchange(),
            routing_key: $event->key(),
        );
    }

    /**
     * @param callable(Message): void $callback
     * @param Events[] $events
     * @return void
     * @throws QueueEventsException
     */
    public function subscribe(callable $callback, array $events): void
    {
        foreach ($events as $event) {
            (new Consumer())->consume(
                callback: $callback,
                queue: $event->queue(),
            );
        }
    }
}
