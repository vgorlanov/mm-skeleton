<?php

declare(strict_types=1);

namespace Modules\Company\API\InformationUpdate;

use Common\Uuid\Uuid;
use Illuminate\Http\Request;

interface InformationUpdate
{
    public function update(Uuid $uuid, Request $request): true;
}
