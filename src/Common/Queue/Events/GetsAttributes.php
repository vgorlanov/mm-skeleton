<?php

declare(strict_types=1);

namespace Common\Queue\Events;

use Common\Queue\Attributes\Event;
use Common\Queue\Attributes\Key;
use Common\Queue\Exceptions\QueueEventsException;
use Common\Queue\RabbitMQ\Exchanges\Exchange;
use Common\Queue\RabbitMQ\Keys;
use Common\Queue\RabbitMQ\Queues\Queue;

trait GetsAttributes
{
    public function key(): ?Keys
    {
        $ref = new \ReflectionClassConstant($this::class, $this->name);
        $classAttributes = $ref->getAttributes(Key::class);

        if (count($classAttributes)) {
            return $classAttributes[0]->newInstance()->key;
        }

        return null;
    }

    /**
     * @return Exchange
     * @throws QueueEventsException
     */
    public function exchange(): Exchange
    {
        $ref = new \ReflectionClassConstant($this::class, $this->name);
        $classAttributes = $ref->getAttributes(\Common\Queue\Attributes\Exchange::class);

        if (count($classAttributes)) {
            return $classAttributes[0]->newInstance()->exchange;
        }

        QueueEventsException::ExchangeNotDefined($this);
    }

    /**
     * @return Queue
     * @throws QueueEventsException
     */
    public function queue(): Queue
    {
        foreach ($this->exchange()->binds() as $bind) {
            if ($bind->key === $this->key()) {
                return $bind->queue;
            }
        }

        QueueEventsException::QueueNotDefined($this);
    }

    /**
     * @return class-string<\Common\Queue\Queueable>
     * @throws QueueEventsException
     */
    public function event(): string
    {
        $ref = new \ReflectionClassConstant($this::class, $this->name);
        $classAttributes = $ref->getAttributes(Event::class);

        if (count($classAttributes)) {
            return $classAttributes[0]->newInstance()->eventName;
        }

        QueueEventsException::EventNotDefined($this);
    }
}
