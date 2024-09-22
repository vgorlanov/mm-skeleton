<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User;

use Modules\User\Domain\events\DataChanged;
use Tests\TestCase;

final class DataUpdateTest extends TestCase
{
    public function test_success(): void
    {
        $user = (new UserBuilder())->build();
        $new = (new UserBuilder())->build();

        $user->changeData(clone $new->getData());

        $this->assertEquals($user->getData(), $new->getData());
        $this->assertNotEmpty($events = $user->events()->release());

        /** @var DataChanged $event */
        $event = end($events);
        $this->assertInstanceOf(DataChanged::class, $event);
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());
        $this->assertSame($user->getUuid(), $event->getUuid());
        $this->assertSame($user->getData(), $event->getData());

        $json = json_encode([
            'uuid'       => $user->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
            'data'       => $user->getData(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }
}
