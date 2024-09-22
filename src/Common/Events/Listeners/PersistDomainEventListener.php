<?php

declare(strict_types=1);

namespace Common\Events\Listeners;

use Common\Events\DomainEvent;
use Common\Events\EventStore\EventStore;
use Common\Events\Exceptions\InvalidListenerException;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * @implements Listener<DomainEvent>
 */
final readonly class PersistDomainEventListener implements Listener
{
    private EventStore $store;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->store = app()->make(EventStore::class);
    }

    public function handle(object $event): void
    {
        try {
            $this->store->append($event);
        } catch (Exception $e) {
            throw new InvalidListenerException('Не возможно сохранить событие', $e->getCode());
        }
    }

    public function rollback(object $event): void
    {
        $this->store->delete($event);
    }
}
