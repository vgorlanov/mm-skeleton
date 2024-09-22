<?php

declare(strict_types=1);

namespace Common\Events;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final readonly class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private ListenerProviderInterface $listenerProvider,
        private LoggerInterface $logger = new NullLogger(),
    ) {}

    /**
     * @throws \Exception
     */
    public function dispatch(object $event): object
    {
        $stoppable = $event instanceof StoppableEventInterface;

        if ($stoppable && $event->isPropagationStopped()) {
            return $event;
        }

        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
            try {
                $listener->handle($event);
            } catch (\Exception $e) {
                $this->logger->warning('Ошибка при обработки события');
                $this->rollback($event);
                throw $e;
            }
        }

        return $event;
    }

    private function rollback(object $event): void
    {
        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
            $listener->rollback($event);
        }
    }
}
