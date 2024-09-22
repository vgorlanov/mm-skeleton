<?php

declare(strict_types=1);

namespace Modules\User\Exceptions;

use Common\Exceptions\BusinessException;
use Common\Exceptions\NotLoggingException;

final class UserBlockedException extends BusinessException implements NotLoggingException
{
    /**
     * @throws UserBlockedException
     */
    public static function alreadyBlocked(): self
    {
        throw new self('Пользователь уже заблокирован');
    }

    /**
     * @throws UserBlockedException
     */
    public static function notBlocked(): self
    {
        throw new self('Пользователь не заблокирован');
    }
}
