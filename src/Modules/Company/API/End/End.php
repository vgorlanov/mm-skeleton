<?php

declare(strict_types=1);

namespace Modules\Company\API\End;

use Common\Uuid\Uuid;

interface End
{
    public function end(Uuid $uuid): true;
}
