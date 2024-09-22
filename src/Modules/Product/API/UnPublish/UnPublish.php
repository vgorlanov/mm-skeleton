<?php

declare(strict_types=1);

namespace Modules\Product\API\UnPublish;

use Common\Uuid\Uuid;

interface UnPublish
{
    public function unPublish(Uuid $uuid): true;
}
