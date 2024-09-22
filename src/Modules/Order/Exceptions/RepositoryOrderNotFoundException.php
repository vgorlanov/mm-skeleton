<?php

declare(strict_types=1);

namespace Modules\Order\Exceptions;

use Common\Uuid\Uuid;

final class RepositoryOrderNotFoundException extends \LogicException
{
    public function __construct(Uuid $uuid)
    {
        parent::__construct('Заказ ' . $uuid->toString() . ' не обнаружен');
    }
}
