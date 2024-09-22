<?php

declare(strict_types=1);

namespace Modules\Product\API\Update;

use Common\Uuid\Uuid;
use Illuminate\Http\Request;

interface Update
{
    public function update(Uuid $uuid, Request $request): true;
}
