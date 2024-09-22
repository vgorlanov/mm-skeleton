<?php

declare(strict_types=1);

namespace Common\Queue\Exceptions;

use Common\Exceptions\BusinessException;
use Common\Exceptions\NotLoggingException;
use Common\Queue\Events\Events;

final class QueueEventsException extends BusinessException implements NotLoggingException
{
    /**
     * @param Events $event
     * @return QueueEventsException
     * @throws QueueEventsException
     */
    public static function ExchangeNotDefined(Events $event): QueueEventsException
    {
        throw new  QueueEventsException('Для события ' . $event->value . ' не определён Exchange');
    }

    /**
     * @param Events $event
     * @return QueueEventsException
     * @throws QueueEventsException
     */
    public static function QueueNotDefined(Events $event): QueueEventsException
    {
        throw new QueueEventsException('Для события ' . $event->value . ' не определёна очередь');
    }

    /**
     * @param Events|null $event
     * @return QueueEventsException
     * @throws QueueEventsException
     */
    public static function EventNotDefined(Events $event = null): QueueEventsException
    {
        if ($event) {
            throw new QueueEventsException('Для события ' . $event->value . ' не определено исходное события');
        }

        throw new QueueEventsException('Событие не определено');
    }
}
