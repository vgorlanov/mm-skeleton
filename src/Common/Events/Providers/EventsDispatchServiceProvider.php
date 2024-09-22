<?php

namespace Common\Events\Providers;

use Common\Events\EventDispatcher;
use Common\Events\EventStore\DBEventStore;
use Common\Events\EventStore\EventStore;
use Common\Events\ListenerProvider;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

class EventsDispatchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * @throws BindingResolutionException
     */
    public function register(): void
    {
        $this->app->singleton(ListenerProviderInterface::class, ListenerProvider::class);

        $listener = $this->app->make(ListenerProviderInterface::class);

        $this->app->singleton(EventDispatcherInterface::class, fn() => new EventDispatcher($listener));

        $this->app->singleton(EventStore::class, DBEventStore::class);
    }
}
