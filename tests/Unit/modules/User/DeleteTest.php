<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User;

use Modules\User\Domain\events\Deleted;
use Modules\User\Domain\Status;
use Modules\User\Exceptions\UserDeletedException;
use Tests\TestCase;

final class DeleteTest extends TestCase
{
    public function test_success(): void
    {
        $user = (new UserBuilder())->build();

        $this->assertSame($user->status()->current(), Status::NEW);

        $user->delete(new \DateTimeImmutable());

        $this->assertSame($user->status()->current(), Status::DELETE);
        $this->assertNotEmpty($events = $user->events()->release());

        /** @var Deleted $event */
        $event = end($events);
        $this->assertInstanceOf(Deleted::class, $event);
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
        $user = (new UserBuilder())->deleted()->build();

        $this->expectException(UserDeletedException::class);
        $user->delete(new \DateTimeImmutable());
    }
}
