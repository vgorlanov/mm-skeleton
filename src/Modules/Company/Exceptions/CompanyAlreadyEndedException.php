<?php

declare(strict_types=1);

namespace Modules\Company\Exceptions;

use Common\Exceptions\BusinessException;
use Common\Exceptions\NotLoggingException;

final class CompanyAlreadyEndedException extends BusinessException implements NotLoggingException
{
    public function __construct()
    {
        parent::__construct('Активность компании уже завершена');
    }
}
