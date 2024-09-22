<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company\services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\dto\RestoreDto;
use Modules\Company\Domain\services\RestoreService;
use Modules\Company\Domain\Status;
use Tests\Unit\modules\Company\CompanyBuilder;

final class RestoreServiceTest extends CompanyService
{
    private Company $company;

    public function test_success(): void
    {
        $service = new RestoreService($this->repository, $this->dispatcher);

        $dto = new RestoreDto($this->company->getUuid(), new \DateTimeImmutable());
        $service->execute($dto);

        $company = $this->repository->get($dto->uuid);

        $this->assertSame($company->status()->current(), Status::NEW);
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->company = (new CompanyBuilder())->deleted()->build();

        $this->repository->add($this->company);
    }
}
