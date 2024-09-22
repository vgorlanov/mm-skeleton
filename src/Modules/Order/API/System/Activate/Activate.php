<?php

declare(strict_types=1);


namespace Modules\Order\API\System\Activate;


use Common\Uuid\Uuid;

interface Activate
{
    public function activate(Uuid $uuid): true;
}
