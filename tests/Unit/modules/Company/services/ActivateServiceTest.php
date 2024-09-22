<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company\services;

use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\ActivateService;
use Modules\Company\Domain\services\dto\ActivateDto;
use Modules\Company\Domain\Status;
use Modules\Company\Exceptions\CompanyAlreadyActivatedException;
use Tests\Unit\modules\Company\CompanyBuilder;

final class ActivateServiceTest extends CompanyService
{
    private Company $company;

    /**
     * @return void
     * @throws CompanyAlreadyActivatedException
     */
    public function test_success(): void
    {
        $service = new ActivateService($this->repository, $this->dispatcher);

        $dto = new ActivateDto($this->company->getUuid(), new \DateTimeImmutable());
        $service->execute($dto);

        $company = $this->repository->get($dto->uuid);

        $this->assertEquals($company->status()->current(), Status::ACTIVE);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = (new CompanyBuilder())->build();

        $this->repository->add($this->company);
    }

}
