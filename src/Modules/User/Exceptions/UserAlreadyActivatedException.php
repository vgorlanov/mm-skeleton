<?php

declare(strict_types=1);

namespace Modules\User\Exceptions;

use Common\Exceptions\BusinessException;
use Common\Exceptions\NotLoggingException;

final class UserAlreadyActivatedException extends BusinessException implements NotLoggingException
{
    public function __construct()
    {
        parent::__construct('Пользователь уже активирован');
    }
}
