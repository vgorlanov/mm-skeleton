<?php

declare(strict_types=1);


namespace Modules\User\API\Info\GetById;


use Common\Uuid\Uuid;

interface GetById
{
    public function get(Uuid $uuid): array;
}
