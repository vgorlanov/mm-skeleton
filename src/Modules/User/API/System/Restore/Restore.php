<?php

declare(strict_types=1);

namespace Modules\User\API\System\Restore;

use Common\Uuid\Uuid;

interface Restore
{
    public function restore(Uuid $uuid): true;
}