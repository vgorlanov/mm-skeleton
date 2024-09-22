<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company\services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\BlockService;
use Modules\Company\Domain\services\dto\BlockDto;
use Modules\Company\Domain\Status;
use Tests\Unit\modules\Company\CompanyBuilder;

final class BlockServiceTest extends CompanyService
{
    private Company $company;

    public function test_success(): void
    {
        $service = new BlockService($this->repository, $this->dispatcher);

        $dto = new BlockDto($this->company->getUuid(), new \DateTimeImmutable());
        $service->execute($dto);

        $company = $this->repository->get($dto->uuid);

        $this->assertSame($company->status()->current(), Status::BLOCK);
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->company = (new CompanyBuilder())->build();

        $this->repository->add($this->company);
    }
}
