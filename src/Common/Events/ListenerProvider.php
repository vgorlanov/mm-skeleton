<?php

declare(strict_types=1);

namespace Common\Events;

use Common\Events\Listeners\Listener;
use Psr\EventDispatcher\ListenerProviderInterface;
use ReflectionClass;

final class ListenerProvider implements ListenerProviderInterface
{
    /**
     * @param object $event
     * @return iterable<\Common\Events\Attributes\Listener>
     */
    public function getListenersForEvent(object $event): iterable
    {
        $ref = new ReflectionClass($event);
        $attributes = $ref->getAttributes();

        foreach ($attributes as $attribute) {
            foreach ($attribute->getArguments() as $listenerClass) {
                /** @var \Common\Events\Attributes\Listener $listener */
                $listener = new $listenerClass($event);
                yield $listener;
            }
        }
    }
}
