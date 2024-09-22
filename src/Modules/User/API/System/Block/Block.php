<?php

declare(strict_types=1);

namespace Modules\User\API\System\Block;

use Common\Uuid\Uuid;

interface Block
{
    public function block(Uuid $uuid): true;
}
