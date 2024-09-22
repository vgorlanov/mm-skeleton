<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services\dto;

use Common\Uuid\Uuid;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(type: 'object')]
final readonly class CompanyDto
{
    public function __construct(
        #[Property(title: 'about')]
        public AboutDto $about,
        #[Property(title: 'contacts')]
        public ContactsDto $contacts,
        #[Property(title: 'information')]
        public InformationDto $information,
        public ?Uuid $uuid = null,
        public ?StatusDto $status = null,
    ) {}
}
