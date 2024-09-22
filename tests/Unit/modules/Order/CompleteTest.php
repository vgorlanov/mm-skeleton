<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order;

use Modules\Order\Domain\events\Complete;
use Modules\Order\Domain\Status;
use Modules\Order\Exceptions\OrderAlreadyCompleteException;
use Tests\TestCase;

final class CompleteTest extends TestCase
{
    public function test_success(): void
    {
        $order = (new OrderBuilder())->build();
        $order->complete(new \DateTimeImmutable());

        $this->assertSame($order->status()->current(), Status::COMPLETE);
        $this->assertNotEmpty($events = $order->events()->release());

        /** @var Complete $event */
        $event = end($events);
        $this->assertInstanceOf(Complete::class, $event);
        $this->assertSame($order->getUuid(), $event->getUuid());

        $json = json_encode([
            'uuid' => $order->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }

    public function test_exception(): void
    {
        $order = (new OrderBuilder())->complete()->build();

        $this->expectException(OrderAlreadyCompleteException::class);
        $order->complete(new \DateTimeImmutable());
    }

}
