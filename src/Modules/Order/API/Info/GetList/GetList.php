<?php

declare(strict_types=1);


namespace Modules\Order\API\Info\GetList;

use Illuminate\Http\JsonResponse;
use Common\Uuid\Uuid;

interface GetList
{
    public function list(int $perPage = 10, int $page = 1, Uuid $company = null): JsonResponse;
}
