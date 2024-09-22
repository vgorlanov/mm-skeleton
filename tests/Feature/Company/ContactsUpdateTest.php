<?php

declare(strict_types=1);

namespace Tests\Feature\Company;

use Tests\Unit\modules\Company\CompanyBuilder;

final class ContactsUpdateTest extends CompanyTest
{
    protected const ROUTE = 'admin.company.contacts.update';

    public function test_success(): void
    {
        $this->putJson($this->url(self::ROUTE, $this->company), (array) $this->company->getContacts())
            ->assertStatus(200);
    }

    public function test_contacts_validation(): void
    {
        $this->putJson($this->url(self::ROUTE, $this->company))
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'position',
                'email',
            ]);

        // format email
        $this->putJson($this->url(self::ROUTE, $this->company), [
            'email'    => 'fail_email',
            'name'     => $this->company->getContacts()->name,
            'position' => $this->company->getContacts()->position,
            'phone'    => $this->company->getContacts()->phone,
        ])
            ->assertJsonValidationErrors([
                'email',
            ])
            ->assertJsonMissingValidationErrors([
                'name',
                'position',
                'phone',
            ]);
    }

    public function test_not_found(): void
    {
        $company = (new CompanyBuilder())->activated()->build();

        $this->putJson($this->url(self::ROUTE, $company), (array) $company->getContacts())->assertStatus(404);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = (new CompanyBuilder())->build();
        $this->repository->add($this->company);
    }
}
