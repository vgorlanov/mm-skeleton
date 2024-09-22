<?php

declare(strict_types=1);

namespace Modules\Company\Exceptions;

use Common\Exceptions\BusinessException;
use Common\Exceptions\NotLoggingException;

final class CompanyBlockedException extends BusinessException implements NotLoggingException
{
    /**
     * @throws CompanyBlockedException
     */
    public static function already(): self
    {
        throw new self('Компания уже заблокирована');
    }

    /**
     * @throws CompanyBlockedException
     */
    public static function not(): self
    {
        throw new self('Компания не заблокирована');
    }
}
