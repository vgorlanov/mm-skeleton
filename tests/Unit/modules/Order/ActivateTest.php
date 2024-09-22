<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order;

use Modules\Order\Domain\events\Activated;
use Modules\Order\Domain\Status;
use Modules\Order\Exceptions\OrderAlreadyActivatedException;
use Tests\TestCase;

final class ActivateTest extends TestCase
{
    /**
     * @return void
     * @throws \JsonException
     * @throws OrderAlreadyActivatedException
     */
    public function test_success(): void
    {
        $order = (new OrderBuilder())->build();
        $order->activate(new \DateTimeImmutable());

        $this->assertSame($order->status()->current(), Status::ACTIVE);
        $this->assertNotEmpty($events = $order->events()->release());

        /** @var Activated $event */
        $event = end($events);
        $this->assertInstanceOf(Activated::class, $event);
        $this->assertSame($order->getUuid(), $event->getUuid());

        $json = json_encode([
            'uuid' => $order->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }

    public function test_exception(): void
    {
        $order = (new OrderBuilder())->activate()->build();

        $this->expectException(OrderAlreadyActivatedException::class);
        $order->activate(new \DateTimeImmutable());
    }

}
