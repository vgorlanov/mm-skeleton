<?php

declare(strict_types=1);

namespace Tests\Feature\Company;

use Tests\Unit\modules\Company\CompanyBuilder;

final class GetByIdTest extends CompanyTest
{
    protected const ROUTE = 'admin.company.show';

    public function test_success(): void
    {
        $company = (new CompanyBuilder())->build();

        $this->repository->add($company);

        $result = $this->get($this->url(self::ROUTE, $company))->json();

        $this->assertEquals($result['about'], (array) $company->getAbout());
        $this->assertEquals($result['contacts'], (array) $company->getContacts());
    }

    public function test_not_found(): void
    {
        $company = (new CompanyBuilder())->activated()->build();

        $this->get($this->url(self::ROUTE, $company))->assertStatus(404);
    }
}
