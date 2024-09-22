<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company\services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\DeleteService;
use Modules\Company\Domain\services\dto\DeleteDto;
use Modules\Company\Domain\Status;
use Modules\Company\Exceptions\CompanyAlreadyActivatedException;
use Modules\Company\Exceptions\CompanyAlreadyEndedException;
use Modules\Company\Exceptions\CompanyBlockedException;
use Modules\Company\Exceptions\CompanyDeletedException;
use Tests\Unit\modules\Company\CompanyBuilder;

final class DeleteServiceTest extends CompanyService
{
    private Company $company;

    /**
     * @return void
     * @throws CompanyDeletedException
     */
    public function test_success(): void
    {
        $service = new DeleteService($this->repository, $this->dispatcher);

        $dto = new DeleteDto($this->company->getUuid(), new \DateTimeImmutable());
        $service->execute($dto);

        $company = $this->repository->get($dto->uuid);

        $this->assertSame($company->status()->current(), Status::DELETE);
    }

    /**
     * @return void
     * @throws BindingResolutionException
     * @throws CompanyDeletedException
     * @throws CompanyAlreadyActivatedException
     * @throws CompanyAlreadyEndedException
     * @throws CompanyBlockedException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->company = (new CompanyBuilder())->build();

        $this->repository->add($this->company);
    }
}
