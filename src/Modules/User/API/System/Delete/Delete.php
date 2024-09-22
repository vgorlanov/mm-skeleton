<?php

declare(strict_types=1);

namespace Modules\User\API\System\Delete;

use Common\Uuid\Uuid;

interface Delete
{
    public function delete(Uuid $uuid): true;
}
