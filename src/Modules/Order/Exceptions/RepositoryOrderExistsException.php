<?php

declare(strict_types=1);

namespace Modules\Order\Exceptions;

use Common\Uuid\Uuid;

final class RepositoryOrderExistsException extends \LogicException
{
    public function __construct(Uuid $uuid)
    {
        parent::__construct('Заказ ' . $uuid->toString() . ' уже существует');
    }
}
