<?php

declare(strict_types=1);

namespace Modules\Company\API\Create;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Company\Domain\services\CreateService;
use Modules\Company\Domain\services\dto\AboutDto;
use Modules\Company\Domain\services\dto\CompanyDto;
use Modules\Company\Domain\services\dto\ContactsDto;
use Modules\Company\Domain\services\dto\InformationDto;
use Modules\Company\Validators\AboutValidator;
use Modules\Company\Validators\CompanyValidator;
use Modules\Company\Validators\ContactsValidator;
use Modules\Company\Validators\InformationValidator;

final readonly class Module implements Create
{
    /**
     * @param CreateService $service
     */
    public function __construct(
        private CreateService $service,
    ) {}

    /**
     * @throws ValidationException
     */
    public function create(Request $request): true
    {
        $this->validate($request);

        $this->service->execute($this->makeDto($request));

        return true;
    }

    /**
     * @throws ValidationException
     */
    private function validate(Request $request): void
    {
        (new CompanyValidator(
            new AboutValidator(),
            new ContactsValidator(),
            new InformationValidator(),
        ))->validate($request);
    }

    /**
     * @param Request $request
     * @return CompanyDto
     */
    private function makeDto(Request $request): CompanyDto
    {
        return new CompanyDto(
            about: new AboutDto(...$request->get('about')),
            contacts: new ContactsDto(...$request->get('contacts')),
            information: InformationDto::make($request->get('information')),
        );
    }
}
