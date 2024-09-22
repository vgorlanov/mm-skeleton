<?php

declare(strict_types=1);

namespace Modules\Company\API\GetById;

use Common\Status\Exceptions\TitleNotFoundException;
use Common\Uuid\Uuid;
use Modules\Company\Domain\Information;
use Modules\Company\Infrastructure\Repository;

/**
 * @phpstan-import-type ResponseCompany from GetById
 * @phpstan-import-type ResponseCompanyInformation from GetById
 */
final readonly class Module implements GetById
{
    public function __construct(
        private Repository $repository,
    ) {}

    /**
     * @param Uuid $uuid
     * @return ResponseCompany
     * @throws TitleNotFoundException
     */
    public function get(Uuid $uuid): array
    {
        $company = $this->repository->get($uuid);

        return [
            'about'       => (array) $company->getAbout(),
            'information' => $this->information($company->getInformation()),
            'contacts'    => (array) $company->getContacts(),
            'status'      => [
                'code' => $company->status()->current()->value, //@phpstan-ignore-line
                'name' => $company->status()->current()->title(),
            ],
        ];
    }

    /**
     * @param Information $information
     * @return ResponseCompanyInformation
     * @throws TitleNotFoundException
     */
    private function information(Information $information): array
    {
        $type = [
            'code' => $information->type->value,
            'name' => $information->type->title(),
        ];

        // todo сравнить с другими решениями, возможно это не самое лучшее
        return array_merge((array) $information, ['type' => $type]);
    }
}
