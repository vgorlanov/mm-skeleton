<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company\services;

use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\dto\InformationDto;
use Modules\Company\Domain\services\InformationUpdateService;
use Tests\Unit\modules\Company\CompanyBuilder;

final class InformationUpdateServiceTest extends CompanyService
{
    private Company $company;

    public function test_success(): void
    {
        $service = new InformationUpdateService($this->repository, $this->dispatcher);
        $new = (new CompanyBuilder())->build();

        $service->execute($this->company->getUuid(), new InformationDto(...(array) $new->getInformation()));

        $company = $this->repository->get($this->company->getUuid());
        $this->assertEquals($company->getInformation(), $new->getInformation());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = (new CompanyBuilder())->build();

        $this->repository->add($this->company);
    }
}
