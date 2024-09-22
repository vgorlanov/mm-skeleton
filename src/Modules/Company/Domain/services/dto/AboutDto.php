<?php

declare(strict_types=1);

namespace Modules\Company\Domain\services\dto;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

#[Schema(description: 'Описание компании', type: 'object')]
final readonly class AboutDto
{
    public function __construct(
        #[Property(title: 'Name', example: 'Company Name')]
        public string $name,
        #[Property(title: 'Country', example: 'Russia')]
        public string $country,
        #[Property(title: 'City', example: 'Moscow')]
        public string $city,
        #[Property(title: 'URL', example: 'www.site.ru')]
        public ?string $url = null,
        #[Property(title: 'Alias', description: 'Алиас компании в урл на сайте', example: '/promo-place')]
        public ?string $alias = null,
        #[Property(title: 'Image', description: 'Логотип компании')]
        public ?string $image = null,
        #[Property(title: 'about', description: 'Публичное описание компании')]
        public ?string $about = null,
    ) {}
}
