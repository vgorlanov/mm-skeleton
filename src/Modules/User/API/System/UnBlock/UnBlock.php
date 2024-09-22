<?php

declare(strict_types=1);

namespace Modules\User\API\System\UnBlock;

use Common\Uuid\Uuid;

interface UnBlock
{
    public function unblock(Uuid $uuid): true;
}
