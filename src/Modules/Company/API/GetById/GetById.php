<?php

declare(strict_types=1);

namespace Modules\Company\API\GetById;

use Common\Uuid\Uuid;

/**
 * @phpstan-type ResponseCompanyType array{code: string, name: string}
 * @phpstan-type ResponseCompanyStatus array{code: string, name: string}
 * @phpstan-type ResponseCompanyAbout array{name: string, country: string, city: string, url: string|null, alias: string|null, image: string|null, about: string|null}
 * @phpstan-type ResponseCompanyInformation array{type: ResponseCompanyType, name: string|null, inn: int|null, kpp: int|null, address: string|null, real: string|null, fio: string|null,
 *     phone: int|null, info: string|null}
 * @phpstan-type ResponseCompanyContacts array{name: string, position: string, email: string, phone: int, comment: string|null}
 * @phpstan-type ResponseCompany array{about: ResponseCompanyAbout, information: ResponseCompanyInformation, contacts: ResponseCompanyContacts,
 *     status: ResponseCompanyStatus}
 */
interface GetById
{
    /**
     * @param Uuid $uuid
     * @return ResponseCompany
     */
    public function get(Uuid $uuid): array;
}
