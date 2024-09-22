<?php

declare(strict_types=1);

namespace Tests\Feature\Company;

use Tests\Unit\modules\Company\CompanyBuilder;

final class InformationUpdateTest extends CompanyTest
{
    protected const ROUTE = 'admin.company.information.update';

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = (new CompanyBuilder())->build();
        $this->repository->add($this->company);
    }

    public function test_success(): void
    {
        $this->putJson($this->url(self::ROUTE, $this->company), (array) $this->company->getInformation())
            ->assertStatus(200);
    }
}
