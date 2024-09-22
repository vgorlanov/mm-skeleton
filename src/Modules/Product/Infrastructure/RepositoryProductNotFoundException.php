<?php

declare(strict_types=1);

namespace Modules\Product\Infrastructure;

use Common\Exceptions\BusinessException;
use Common\Exceptions\NotLoggingException;

final class RepositoryProductNotFoundException extends BusinessException implements NotLoggingException
{
    public function __construct(string $uuid)
    {
        parent::__construct('Продукт ' . $uuid . ' не обнаружен');
    }
}
