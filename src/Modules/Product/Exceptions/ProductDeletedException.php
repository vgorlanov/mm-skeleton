<?php

declare(strict_types=1);

namespace Modules\Product\Exceptions;

use Common\Exceptions\BusinessException;
use Common\Exceptions\NotLoggingException;

final class ProductDeletedException extends BusinessException implements NotLoggingException
{
    /**
     * @throws ProductDeletedException
     */
    public static function already(): self
    {
        throw new self('Продукт уже удалён');
    }

    /**
     * @throws ProductDeletedException
     */
    public static function not(): self
    {
        throw new self('Продукт не удалён');
    }
}
