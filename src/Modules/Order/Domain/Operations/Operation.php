<?php

declare(strict_types=1);

namespace Modules\Order\Domain\Operations;

use Modules\Order\Domain\Price;

interface Operation
{
    public function calc(): Price;
}
