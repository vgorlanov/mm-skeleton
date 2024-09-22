<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User;

use Modules\User\Domain\events\Activated;
use Modules\User\Domain\Status;
use Modules\User\Exceptions\UserAlreadyActivatedException;
use Tests\TestCase;

final class ActivateTest extends TestCase
{
    /**
     * @return void
     * @throws \JsonException
     * @throws \Modules\User\Exceptions\UserAlreadyActivatedException
     */
    public function test_success(): void
    {
        $user = (new UserBuilder())->build();

        $this->assertSame($user->status()->current(), Status::NEW);

        $user->activate(new \DateTimeImmutable());

        $this->assertSame($user->status()->current(), Status::ACTIVE);
        $this->assertNotEmpty($events = $user->events()->release());

        /** @var Activated $event */
        $event = end($events);
        $this->assertInstanceOf(Activated::class, $event);
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
        $user = (new UserBuilder())->activated()->build();

        $this->expectException(UserAlreadyActivatedException::class);
        $user->activate(new \DateTimeImmutable());
    }
}
