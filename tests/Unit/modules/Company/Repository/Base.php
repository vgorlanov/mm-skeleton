<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company\Repository;

use Common\Uuid\Uuid;
use Faker\Factory;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyExistsException;
use Modules\Company\Infrastructure\Exceptions\RepositoryCompanyNotFoundException;
use Modules\Company\Infrastructure\Repository;
use Tests\TestCase;
use Tests\Unit\modules\Company\CompanyBuilder;

abstract class Base extends TestCase
{
    abstract public function repository(): Repository;

    public function test_get_exception(): void
    {
        $this->expectException(RepositoryCompanyNotFoundException::class);
        $this->repository()->get(Uuid::next());
    }

    public function test_add_success(): void
    {
        $new = (new CompanyBuilder())->build();

        $this->repository()->add($new);

        $company = $this->repository()->get($new->getUuid());

        $this->assertEquals($company->getUuid(), $new->getUuid());
        $this->assertEquals($company->getAbout(), $new->getAbout());
        $this->assertEquals($company->getDate(), $new->getDate());
        $this->assertEquals($company->getContacts(), $new->getContacts());
        $this->assertEquals($company->getInformation(), $new->getInformation());
    }

    public function test_add_exception(): void
    {
        $company = (new CompanyBuilder())->build();

        $this->repository()->add($company);

        $this->expectException(RepositoryCompanyExistsException::class);
        $this->repository()->add($company);
    }

    public function test_update_success(): void
    {
        $uuid = Uuid::next();

        $company = (new CompanyBuilder())->withUuid($uuid)->build();
        $update = (new CompanyBuilder())->withUuid($uuid)->build();

        $this->repository()->add($company);
        $this->repository()->update($update);

        $updated = $this->repository()->get($uuid);

        $this->assertEquals($updated->getUuid(), $company->getUuid());
        $this->assertNotEquals($updated->getAbout(), $company->getAbout());
        $this->assertNotEquals($updated->getDate(), $company->getDate());
        $this->assertNotEquals($updated->getContacts(), $company->getContacts());
        $this->assertNotEquals($updated->getInformation(), $company->getInformation());
    }

    public function test_update_exception(): void
    {
        $company = (new CompanyBuilder())->build();
        $this->expectException(RepositoryCompanyNotFoundException::class);
        $this->repository()->update($company);
    }

    public function test_remove_success(): void
    {
        $company = (new CompanyBuilder())->build();
        $this->repository()->add($company);
        $this->repository()->remove($company);

        $this->expectException(RepositoryCompanyNotFoundException::class);
        $this->repository()->get($company->getUuid());
    }

    public function test_remove_exception(): void
    {
        $company = (new CompanyBuilder())->build();

        $this->expectException(RepositoryCompanyNotFoundException::class);
        $this->repository()->remove($company);
    }

    public function test_has_alias_exists(): void
    {
        $company = (new CompanyBuilder())->build();

        $this->repository()->add($company);

        $this->assertTrue($this->repository()->hasAlias($company->getAbout()->alias));
    }

    public function test_has_alias_not_exists(): void
    {
        $company = (new CompanyBuilder())->build();
        $this->repository()->add($company);
        $this->assertFalse($this->repository()->hasAlias(Factory::create()->word()));
    }
}
