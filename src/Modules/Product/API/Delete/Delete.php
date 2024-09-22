<?php

declare(strict_types=1);

namespace Modules\Product\API\Delete;

use Common\Uuid\Uuid;

interface Delete
{
    public function delete(Uuid $uuid): true;
}
