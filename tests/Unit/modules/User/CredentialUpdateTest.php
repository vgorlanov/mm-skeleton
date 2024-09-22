<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User;

use Modules\User\Domain\events\CredentialChanged;
use Tests\TestCase;

final class CredentialUpdateTest extends TestCase
{
    public function test_success(): void
    {
        $user = (new UserBuilder())->build();
        $new = (new UserBuilder())->build();

        $user->changeCredential(clone $new->getCredential());

        $this->assertEquals($user->getCredential(), $new->getCredential());
        $this->assertNotEmpty($events = $user->events()->release());

        /** @var CredentialChanged $event */
        $event = end($events);
        $this->assertInstanceOf(CredentialChanged::class, $event);
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());
        $this->assertSame($user->getUuid(), $event->getUuid());
        $this->assertSame($user->getCredential(), $event->getCredential());

        $json = json_encode([
            'uuid'       => $user->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
            'credential' => $user->getCredential(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }
}
