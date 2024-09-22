<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order;

use Modules\Order\Domain\events\Cancel;
use Modules\Order\Domain\Status;
use Modules\Order\Exceptions\OrderAlreadyCanceledException;
use Tests\TestCase;

final class CancelTest extends TestCase
{
    public function test_success(): void
    {
        $order = (new OrderBuilder())->build();
        $order->cancel(new \DateTimeImmutable());

        $this->assertSame($order->status()->current(), Status::CANCEL);
        $this->assertNotEmpty($events = $order->events()->release());

        /** @var Cancel $event */
        $event = end($events);
        $this->assertInstanceOf(Cancel::class, $event);
        $this->assertSame($order->getUuid(), $event->getUuid());

        $json = json_encode([
            'uuid' => $order->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }

    public function test_exception(): void
    {
        $order = (new OrderBuilder())->cancel()->build();

        $this->expectException(OrderAlreadyCanceledException::class);
        $order->cancel(new \DateTimeImmutable());
    }
}
