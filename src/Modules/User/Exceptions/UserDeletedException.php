<?php

declare(strict_types=1);

namespace Modules\User\Exceptions;

use Common\Exceptions\BusinessException;
use Common\Exceptions\NotLoggingException;

final class UserDeletedException extends BusinessException implements NotLoggingException
{
    /**
     * @throws UserDeletedException
     */
    public static function alreadyDeleted(): self
    {
        throw new self('Пользователь уже удалён');
    }

    /**
     * @throws UserDeletedException
     */
    public static function notDeleted(): self
    {
        throw new self('Пользователь не удалён');
    }
}
