<?php

declare(strict_types=1);

namespace Modules\User\API\System\DataUpdate;

use Common\Uuid\Uuid;
use Illuminate\Http\Request;

interface DataUpdate
{
    public function update(Uuid $uuid, Request $request): true;
}