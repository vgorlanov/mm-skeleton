<?php

declare(strict_types=1);

namespace Tests\Feature\Company;

use Modules\Company\Domain\Status;
use Tests\Unit\modules\Company\CompanyBuilder;

final class BlockTest extends CompanyTest
{
    protected const ROUTE = 'admin.company.block';

    public function test_success(): void
    {
        $company = (new CompanyBuilder())->build();

        $this->repository->add($company);

        $this->patch($this->url(self::ROUTE, $company))->assertStatus(200);

        $company = $this->repository->get($company->getUuid());

        $this->assertSame($company->status()->current(), Status::BLOCK);
    }

    public function test_validation(): void
    {
        $company = (new CompanyBuilder())->blocked()->build();

        $this->repository->add($company);

        $this->patch($this->url(self::ROUTE, $company))->assertStatus(422);
    }

    public function test_not_found(): void
    {
        $company = (new CompanyBuilder())->activated()->build();

        $this->patch($this->url(self::ROUTE, $company))->assertStatus(404);
    }
}
