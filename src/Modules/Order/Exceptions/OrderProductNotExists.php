<?php

declare(strict_types=1);

namespace Modules\Order\Exceptions;

use Common\Exceptions\BusinessException;
use Common\Exceptions\NotLoggingException;

final class OrderProductNotExists extends BusinessException implements NotLoggingException
{
    public function __construct()
    {
        parent::__construct('Product not exists!');
    }

}
