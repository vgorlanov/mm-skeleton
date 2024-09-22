<?php

declare(strict_types=1);

namespace Tests\Feature\Company;

use Tests\Unit\modules\Company\CompanyBuilder;

final class AboutUpdateTest extends CompanyTest
{
    protected const ROUTE = 'admin.company.about.update';

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = (new CompanyBuilder())->build();
        $this->repository->add($this->company);
    }

    public function test_success(): void
    {
        $this->putJson($this->url(self::ROUTE, $this->company), (array) $this->company->getAbout())
            ->assertStatus(200);
    }

    public function test_contacts_validation(): void
    {
        $this->putJson($this->url(self::ROUTE, $this->company))
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'country',
                'city',
            ]);

        // name
        $this->putJson($this->url(self::ROUTE, $this->company), [
            'position' => $this->company->getAbout()->country,
            'phone' => $this->company->getAbout()->city,
        ])
            ->assertJsonValidationErrors([
                'name',
            ])
            ->assertJsonMissingValidationErrors([
                'position',
                'phone',
            ]);
    }
}
