<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company;

use Common\Uuid\Uuid;
use DateTimeImmutable;
use Exception;
use Faker\Factory;
use Modules\Company\Domain\About;
use Modules\Company\Domain\Company;
use Modules\Company\Domain\Contacts;
use Modules\Company\Domain\events\Created;
use Modules\Company\Domain\Information;
use Modules\Company\Domain\Type;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_success(): void
    {
        $faker = Factory::create();

        $company = new Company(
            $uuid = Uuid::next(),
            $about = new About(
                name: $faker->company,
                country: $faker->country,
                city: $faker->city,
                url: $faker->url,
                alias: $faker->word,
                image: $faker->imageUrl(),
                about: $faker->paragraph,
            ),
            $contacts = new Contacts(
                name: $faker->name,
                position: $faker->word,
                email: $faker->email,
                phone: randomPhone(),
                comment: $faker->paragraph,
            ),
            $information = new Information(
                type: Type::cases()[array_rand(Type::cases())],
                name: $faker->company,
                inn: randomPhone(),
                kpp: randomPhone(),
                address: $faker->address,
                real: $faker->address,
                fio: $faker->name,
                phone: randomPhone(),
                info: $faker->paragraph,
            ),
            $created_at = new DateTimeImmutable(),
        );

        $this->assertSame($uuid, $company->getUuid());
        $this->assertSame($about, $company->getAbout());
        $this->assertSame($contacts, $company->getContacts());
        $this->assertSame($information, $company->getInformation());
        $this->assertSame($created_at, $company->getDate());

        $this->assertNotEmpty($events = $company->events()->release());

        /** @var Created $event */
        $event = end($events);
        $this->assertInstanceOf(Created::class, $event);
        $this->assertSame($company->getUuid(), $event->getUuid());

        $json = json_encode([
            'uuid'       => $company->getUuid()->toString(),
            'occurredOn' => $event->occurredOn(),
        ], JSON_THROW_ON_ERROR);

        $this->assertSame($json, $event->toJson());
    }
}
