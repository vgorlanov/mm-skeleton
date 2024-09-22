<?php

declare(strict_types=1);

namespace Modules\Company\API\Unblock;

use Common\Uuid\Uuid;

interface Unblock
{
    public function unblock(Uuid $uuid): true;
}
