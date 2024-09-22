<?php

declare(strict_types=1);

namespace Tests\Feature\Company;

use Tests\Unit\modules\Company\CompanyBuilder;

final class CreateTest extends CompanyTest
{
    private const string ROUTE = 'admin.company.create';

    public function test_success(): void
    {
        $this->postJson($this->url(self::ROUTE), [
            'about'       => (array) $this->company->getAbout(),
            'contacts'    => (array) $this->company->getContacts(),
            'information' => json_decode($this->company->getInformation()->toJSON()),
        ])
            ->assertStatus(201);
    }

    public function test_about_validation(): void
    {
        $this->postJson($this->url(self::ROUTE), [
            'about' => (array) $this->company->getAbout(),
        ])
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors([
                'about.name',
                'about.country',
                'about.city',
            ])
            ->assertJsonValidationErrors([
                'contacts.name',
                'contacts.position',
                'contacts.email',

                'information.type',
            ]);
    }

    public function test_contacts_validation(): void
    {
        $this->postJson($this->url(self::ROUTE), [
            'contacts' => (array) $this->company->getContacts(),
        ])
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors([
                'contacts.name',
                'contacts.position',
                'contacts.email',
                'contacts.phone',
            ])
            ->assertJsonValidationErrors([
                'about.name',
                'about.country',
                'about.city',

                'information.type',
            ]);

        // format email
        $this->postJson($this->url(self::ROUTE), [
            'contacts' => [
                'email'    => 'fail_email',
                'name'     => $this->company->getContacts()->name,
                'position' => $this->company->getContacts()->position,
                'phone'    => $this->company->getContacts()->phone,
            ],
        ])
            ->assertJsonValidationErrors([
                'contacts.email',
            ])
            ->assertJsonMissingValidationErrors([
                'contacts.name',
                'contacts.position',
                'contacts.phone',
            ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = (new CompanyBuilder())->build();
    }
}
