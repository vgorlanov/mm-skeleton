<?php

declare(strict_types=1);

namespace Tests\Unit\modules\Company\services;

use Modules\Company\Domain\Company;
use Modules\Company\Domain\services\ContactsUpdateService;
use Modules\Company\Domain\services\dto\ContactsDto;
use Tests\Unit\modules\Company\CompanyBuilder;

final class ContactsUpdateServiceTest extends CompanyService
{
    private Company $company;

    /**
     * @throws \JsonException
     */
    public function test_success(): void
    {
        $service = new ContactsUpdateService($this->repository, $this->dispatcher);

        $new = (new CompanyBuilder())->build();

        $service->execute($this->company->getUuid(), new ContactsDto(...(array) $new->getContacts()));

        $company = $this->repository->get($this->company->getUuid());

        $this->assertEquals($company->getContacts(), $new->getContacts());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = (new CompanyBuilder())->build();

        $this->repository->add($this->company);
    }
}
