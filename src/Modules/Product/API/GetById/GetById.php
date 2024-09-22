<?php

declare(strict_types=1);

namespace Modules\Product\API\GetById;

use Common\Uuid\Uuid;

/**
 * @phpstan-type ResponseProduct array{uuid: string, company: string, title: string, body: string, params: string[]|null, images: string[]|null}
 */
interface GetById
{
    /**
     * @param Uuid $uuid
     * @return ResponseProduct
     */
    public function get(Uuid $uuid): array;
}
