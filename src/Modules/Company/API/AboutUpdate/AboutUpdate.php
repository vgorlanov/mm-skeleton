<?php

declare(strict_types=1);

namespace Modules\Company\API\AboutUpdate;

use Common\Uuid\Uuid;
use Illuminate\Http\Request;

interface AboutUpdate
{
    public function update(Uuid $uuid, Request $request): true;
}
