<?php

declare(strict_types=1);


namespace Modules\Order\API\System\Cancel;


use Common\Uuid\Uuid;

interface Cancel
{
    public function cancel(Uuid $uuid): true;
}
