<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User;

use Modules\User\Domain\events\Blocked;
use Modules\User\Domain\Status;
use Modules\User\Exceptions\UserBlockedException;
use Tests\TestCase;

final class BlockTest extends TestCase
{
    public function test_success(): void
    {
        $user = (new UserBuilder())->build();

        $this->assertSame($user->status()->current(), Status::NEW);

        $user->block(new \DateTimeImmutable());

        $this->assertSame($user->status()->current(), Status::BLOCK);
        $this->assertNotEmpty($events = $user->events()->release());

        /** @var Blocked $event */
        $event = end($events);
        $this->assertInstanceOf(Blocked::class, $event);
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());
        $this->assertSame($user->getUuid(), $event->getUuid());

        $json = json_encode([
            'uuid'       => $user->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }

    public function test_exception(): void
    {
        $user = (new UserBuilder())->blocked()->build();

        $this->expectException(UserBlockedException::class);
        $user->block(new \DateTimeImmutable());
    }
}
