<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company\services;

use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\AboutUpdateService;
use Modules\Company\Domain\services\dto\AboutDto;
use Tests\Unit\modules\Company\CompanyBuilder;

final class AboutUpdateServiceTest extends CompanyService
{
    private Company $company;

    public function test_success(): void
    {
        $service = new AboutUpdateService($this->repository, $this->dispatcher);

        $new = (new CompanyBuilder())->build();

        $service->execute($this->company->getUuid(), new AboutDto(...(array) $new->getAbout()));

        $company = $this->repository->get($this->company->getUuid());
        $this->assertEquals($company->getAbout(), $new->getAbout());
        $this->assertNotSame($company->getAbout(), $new->getAbout());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = (new CompanyBuilder())->build();

        $this->repository->add($this->company);
    }
}
