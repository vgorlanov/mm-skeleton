<?php

declare(strict_types=1);

namespace Modules\Product\API\GetById;

use Common\Uuid\Uuid;
use Modules\Product\Infrastructure\Repository;

final readonly class Module implements GetById
{
    public function __construct(
        private Repository $repository,
    ) {}

    public function get(Uuid $uuid): array
    {
        $product = $this->repository->get($uuid);

        return [
            'uuid'    => $product->getUuid()->toString(),
            'title'   => $product->getTitle(),
            'body'    => $product->getBody(),
            'params'  => $product->getParams(),
            'images'  => $product->getImages(),
            'company' => $product->getCompany()->toString(),
        ];
    }
}
