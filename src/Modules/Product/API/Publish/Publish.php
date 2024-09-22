<?php

declare(strict_types=1);

namespace Modules\Product\API\Publish;

use Common\Uuid\Uuid;

interface Publish
{
    public function publish(Uuid $uuid): true;
}
