<?php

declare(strict_types=1);

namespace Modules\Product\Domain\services\dto;

use Common\Uuid\Uuid;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

/**
 * @phpstan-type ProductRequest array{company: string, title: string, body: string, params: array<mixed>|null, images: array<mixed>|null, uuid: string|null}
 */
#[Schema(type: 'object')]
final readonly class ProductDto
{
    /**
     * @param Uuid $company
     * @param string $title
     * @param string $body
     * @param array<string>|null $params
     * @param array<string>|null $images
     * @param Uuid|null $uuid
     */
    public function __construct(
        #[Property(title: 'Company', description: 'Компания', example: 'b8ab3f79-cb9d-41e4-894a-fa75c0a07160')]
        public Uuid $company,
        #[Property(title: 'Title', description: 'Название продукта', type: 'string', example: 'Название продукта')]
        public string $title,
        #[Property(title: 'Body', description: 'Описание', type: 'string', example: 'Описание продукта')]
        public string $body,
        #[Property(title: 'Params', description: 'Параметры', type: 'object')]
        public ?array $params = null,
        #[Property(title: 'Images', description: 'Изображения', type: 'object')]
        public ?array $images = null,
        public ?Uuid $uuid = null,
    ) {}

    /**
     * @param ProductRequest $params
     * @return self
     */
    public static function make(array $params): self
    {
        $params['company'] = new Uuid($params['company']);

        if(isset($params['uuid'])) {
            $params['uuid'] = new Uuid($params['uuid']);
        }

        return new self(...$params);
    }
}
