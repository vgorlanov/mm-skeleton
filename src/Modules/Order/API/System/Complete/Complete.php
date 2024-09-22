<?php

declare(strict_types=1);


namespace Modules\Order\API\System\Complete;


use Common\Uuid\Uuid;

interface Complete
{
    public function complete(Uuid $uuid): true;
}
