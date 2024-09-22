<?php

declare(strict_types=1);

namespace Tests\Unit\modules\User;

use Common\Uuid\Uuid;
use Faker\Factory;
use Modules\User\Domain\Credential;
use Modules\User\Domain\Data;
use Modules\User\Domain\events\Created;
use Modules\User\Domain\Gender;
use Modules\User\Domain\User;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    public function test_success(): void
    {
        $faker = Factory::create();
        $gender = Gender::cases()[array_rand(Gender::cases())];

        $user = new User(
            uuid: $uuid = Uuid::next(),
            credential: $credential = new  Credential(
                email: $faker->email,
                phone: random_int(11111111111, 99999999999),
            ),
            data: $data = new Data(
                name: $faker->firstName($gender->value),
                surname: $faker->lastName,
                gender: Gender::cases()[array_rand(Gender::cases())],
            ),
            date: $date = new \DateTimeImmutable(),
        );

        $this->assertSame($uuid, $user->getUuid());
        $this->assertSame($data, $user->getData());
        $this->assertSame($date, $user->getDate());

        $this->assertNotEmpty($events = $user->events()->release());

        /** @var Created $event */
        $event = end($events);
        $this->assertInstanceOf(Created::class, $event);
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->occurredOn());
        $this->assertSame($user->getUuid(), $event->getUuid());

        $json = json_encode([
            'uuid'       => $user->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }
}
