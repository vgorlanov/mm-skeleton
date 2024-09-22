<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Order;

use Common\Uuid\Uuid;
use Modules\Order\Domain\events\Created;
use Modules\Order\Domain\Order;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    public function test_create_success(): void
    {
        $order = new Order(
            uuid: $uuid = Uuid::next(),
            company: $company = Uuid::next(),
            customer: $customer = (new CustomerBuilder())->build(),
            date: $date = new \DateTimeImmutable(),
        );

        $this->assertSame($uuid, $order->getUuid());
        $this->assertSame($company, $order->getCompany());
        $this->assertSame($customer, $order->getCustomer());
        $this->assertSame($date, $order->getDate());

        $this->assertNotEmpty($events = $order->events()->release());

        /** @var Created $event */
        $event = end($events);
        $this->assertInstanceOf(Created::class, $event);
        $this->assertSame($order->getUuid(), $event->getUuid());
        // todo тело event-а
    }
}
