<?php

declare(strict_types=1);

namespace Modules\User\API\System\CredentialUpdate;

use Common\Uuid\Uuid;
use Illuminate\Http\Request;

interface CredentialUpdate
{
    public function update(Uuid $uuid, Request $request): true;
}
