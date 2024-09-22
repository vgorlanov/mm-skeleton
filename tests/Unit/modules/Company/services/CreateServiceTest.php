<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company\services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Modules\Company\Domain\services\CreateService;
use Modules\Company\Domain\services\dto\AboutDto;
use Modules\Company\Domain\services\dto\CompanyDto;
use Modules\Company\Domain\services\dto\ContactsDto;
use Modules\Company\Domain\services\dto\InformationDto;
use Modules\Company\Exceptions\CompanyAlreadyActivatedException;
use Modules\Company\Exceptions\CompanyAlreadyEndedException;
use Modules\Company\Exceptions\CompanyBlockedException;
use Modules\Company\Exceptions\CompanyDeletedException;
use Tests\Unit\modules\Company\CompanyBuilder;

final class CreateServiceTest extends CompanyService
{
    private CompanyDto $companyCreate;

    public function test_success(): void
    {
        $service = new CreateService($this->repository, $this->dispatcher);

        $company = $service->execute($this->companyCreate);

        $this->assertEquals($this->repository->get($company->getUuid()), $company);
        $this->assertNotSame($this->repository->get($company->getUuid()), $company);
    }

    /**
     * @return void
     * @throws BindingResolutionException
     * @throws CompanyAlreadyActivatedException
     * @throws CompanyAlreadyEndedException
     * @throws CompanyBlockedException
     * @throws CompanyDeletedException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $company = (new CompanyBuilder())->build();

        $this->companyCreate = new CompanyDto(
            about: new AboutDto(...(array) $company->getAbout()),
            contacts: new ContactsDto(...(array) $company->getContacts()),
            information: new InformationDto(...(array) $company->getInformation()),
        );

    }
}
