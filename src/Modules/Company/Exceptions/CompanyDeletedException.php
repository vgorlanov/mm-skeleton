<?php

declare(strict_types=1);

namespace Modules\Company\Exceptions;

use Common\Exceptions\BusinessException;
use Common\Exceptions\NotLoggingException;

final class CompanyDeletedException extends BusinessException implements NotLoggingException
{
    /**
     * @throws CompanyDeletedException
     */
    public static function already(): self
    {
        throw new self('Компания уже удалена');
    }

    /**
     * @throws CompanyDeletedException
     */
    public static function not(): self
    {
        throw new self('Компания не удалена');
    }
}
