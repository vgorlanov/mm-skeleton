<?php

declare(strict_types=1);

namespace Modules\User\Infrastructure;

use Common\Exceptions\BusinessException;
use Common\Exceptions\NotLoggingException;

final class RepositoryUserAlreadyExistsException extends BusinessException implements NotLoggingException
{
    public function __construct(string $uuid)
    {
        parent::__construct('Пользователь ' . $uuid . ' уже существует');
    }
}
