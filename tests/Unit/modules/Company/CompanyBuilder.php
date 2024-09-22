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
use Modules\Company\Domain\Information;
use Modules\Company\Domain\Type;
use Modules\Company\Exceptions\CompanyAlreadyActivatedException;
use Modules\Company\Exceptions\CompanyAlreadyEndedException;
use Modules\Company\Exceptions\CompanyBlockedException;
use Modules\Company\Exceptions\CompanyDeletedException;

final class CompanyBuilder
{
    private Uuid $uuid;
    private About $about;
    private Contacts $contacts;
    private Information $information;
    private DateTimeImmutable $date;
    private bool $blocked = false;
    private bool $active = false;
    private bool $delete = false;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $faker = Factory::create();

        $this->uuid = Uuid::next();
        $this->about = new About(
            name: $faker->company,
            country: $faker->country,
            city: $faker->city,
            url: $faker->url,
            alias: $faker->word,
            image: $faker->imageUrl(),
            about: $faker->paragraph,
        );

        $this->contacts = new Contacts(
            name: $faker->name,
            position: $faker->word,
            email: $faker->email,
            phone: randomPhone(),
            comment: $faker->paragraph,
        );

        $this->information = new Information(
            type: Type::cases()[array_rand(Type::cases())],
            name: $faker->company,
            inn: randomPhone(),
            kpp: randomPhone(),
            address: $faker->address,
            real: $faker->address,
            fio: $faker->name,
            phone: randomPhone(),
            info: $faker->paragraph,
        );

        $this->date = new DateTimeImmutable();
    }

    public function withUuid(Uuid $uuid): self
    {
        $clone = clone $this;
        $clone->uuid = $uuid;
        return $clone;
    }

    public function withAbout(About $about): self
    {
        $clone = clone $this;
        $clone->about = $about;
        return $clone;
    }

    public function withContacts(Contacts $contacts): self
    {
        $clone = clone $this;
        $clone->contacts = $contacts;
        return $clone;
    }

    public function withInformation(Information $information): self
    {
        $clone = clone $this;
        $clone->information = $information;
        return $clone;
    }

    public function blocked(): self
    {
        $clone = clone $this;
        $clone->blocked = true;
        return $clone;
    }

    public function activated(): self
    {
        $clone = clone $this;
        $clone->active = true;
        return $clone;
    }

    public function deleted(): self
    {
        $clone = clone $this;
        $clone->delete = true;
        return $clone;
    }

    /**
     * @return Company
     * @throws CompanyAlreadyActivatedException
     * @throws CompanyBlockedException
     * @throws CompanyDeletedException
     */
    public function build(): Company
    {
        $company = new Company(
            uuid: $this->uuid,
            about: $this->about,
            contacts: $this->contacts,
            information: $this->information,
            date: $this->date,
        );

        if ($this->active) {
            $company->activate(new DateTimeImmutable());
        }

        if ($this->blocked) {
            $company->block(new DateTimeImmutable());
        }

        if ($this->delete) {
            $company->delete(new DateTimeImmutable());
        }

        return $company;
    }
}
