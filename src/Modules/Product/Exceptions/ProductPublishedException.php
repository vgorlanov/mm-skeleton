<?php

declare(strict_types=1);

namespace Modules\Product\Exceptions;

use Common\Exceptions\BusinessException;
use Common\Exceptions\NotLoggingException;

final class ProductPublishedException extends BusinessException implements NotLoggingException
{
    /**
     * @throws ProductPublishedException
     */
    public static function already(): self
    {
        throw new self('Продукт уже опубликован');
    }

    /**
     * @throws ProductPublishedException
     */
    public static function not(): self
    {
        throw new self('Продукт не опубликован');
    }
}
