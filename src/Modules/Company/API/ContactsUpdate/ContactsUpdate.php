<?php

declare(strict_types=1);

namespace Modules\Company\API\ContactsUpdate;

use Common\Uuid\Uuid;
use Illuminate\Http\Request;

interface ContactsUpdate
{
    public function update(Uuid $uuid, Request $request): true;
}
