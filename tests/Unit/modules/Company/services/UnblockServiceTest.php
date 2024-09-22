<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company\services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\dto\UnblockDto;
use Modules\Company\Domain\services\UnblockService;
use Modules\Company\Domain\Status;
use Tests\Unit\modules\Company\CompanyBuilder;

final class UnblockServiceTest extends CompanyService
{
    private Company $company;

    /**
     * @return void
     * @throws \Modules\Company\Exceptions\CompanyBlockedException
     */
    public function test_success(): void
    {
        $service = new UnblockService($this->repository, $this->dispatcher);

        $dto = new UnblockDto($this->company->getUuid(), new \DateTimeImmutable());
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

        $this->company = (new CompanyBuilder())->blocked()->build();

        $this->repository->add($this->company);
    }
}
