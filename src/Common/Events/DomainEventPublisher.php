<?php

declare(strict_types=1);

namespace Common\Events;

use BadMethodCallException;
use Common\EntityAggregate;

final class DomainEventPublisher
{
    private EntityAggregate $entity;

    /** @var array<string, array<DomainEvent>> */
    private array $events = [];

    private static DomainEventPublisher|null $instance = null;

    public static function instance(): DomainEventPublisher
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setEntity(EntityAggregate $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    public function publish(DomainEvent $event): void
    {
        $uuid = $this->entity->getUuid()->toString();

        if (isset($this->events[$uuid])) {
            $this->events[$uuid][] = $event;
        } else {
            $this->events[$uuid] = [$event];
        }
    }

    /**
     * @return array|DomainEvent[]
     */
    public function release(): array
    {
        $uuid = $this->entity->getUuid()->toString();

        if (isset($this->events[$uuid])) {
            $events = $this->events[$uuid];
            unset($this->events[$uuid]);
            return $events;
        }
        return [];
    }

    private function __construct() {}

    public function __clone(): void
    {
        throw new BadMethodCallException('Clone is not supported');
    }
}
