<?php

declare(strict_types=1);

namespace Modules\Order\Infrastructure;

use Common\Uuid\Uuid;
use Modules\Order\Domain\Order;

interface Repository
{
    public function get(Uuid $uuid): Order;

    public function add(Order $order): void;

    public function update(Order $order): void;

    public function remove(Order $order): void;
}
