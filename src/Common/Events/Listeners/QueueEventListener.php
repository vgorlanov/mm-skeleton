<?php

declare(strict_types=1);

namespace Common\Events\Listeners;

use Common\Queue\Events\Events;
use Common\Queue\Exceptions\QueueEventsException;
use Common\Queue\Message;
use Common\Queue\Queue;
use Common\Queue\Queueable;

/**
 * @implements Listener<Queueable>
 */
final readonly class QueueEventListener implements Listener
{
    /**
     * @throws QueueEventsException
     * @throws \JsonException
     */
    public function handle(object $event): void
    {
        $message = new Message(Events::forEvent($event::class)->value, $event->toArray());

        (new Queue())->send($message);
    }

    public function rollback(object $event): void
    {
        // skip
    }
}
