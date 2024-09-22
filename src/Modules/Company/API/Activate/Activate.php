<?php

declare(strict_types=1);

namespace Modules\Company\API\Activate;

use Common\Uuid\Uuid;

interface Activate
{
    public function activate(Uuid $uuid): true;
}
