<?php

declare(strict_types=1);

namespace Tests\Unit\common\Events;

use Common\Events\DomainEvent;
use Common\Events\EventDispatcher;
use PHPUnit\Framework\MockObject\Exception;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Tests\TestCase;

final class EventDispatcherTest extends TestCase
{
    private EventDispatcher $dispatcher;

    private ListenerProviderInterface $provider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = $this->createMock(ListenerProviderInterface::class);
        $this->dispatcher = new EventDispatcher($this->provider);
    }

    /**
     * @throws Exception
     */
    public function test_dispatch_success(): void
    {
        $event = $this->createMock(DomainEvent::class);

        $this->provider
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event);

        $this->assertSame($event, $this->dispatcher->dispatch($event));
    }

    /**
     * @throws Exception
     */
    public function test_propagation_success(): void
    {
        $event = $this->createMock(StoppableEventInterface::class);
        $event
            ->method('isPropagationStopped')
            ->willReturn(true);

        $this->provider
            ->expects($this->never())
            ->method('getListenersForEvent')
            ->with($event);

        $this->assertSame($event, $this->dispatcher->dispatch($event));
    }
}
