<?php

declare(strict_types=1);

namespace Modules\Company\Exceptions;

use Common\Exceptions\BusinessException;
use Common\Exceptions\NotLoggingException;

final class CompanyAlreadyActivatedException extends BusinessException implements NotLoggingException
{
    public function __construct()
    {
        parent::__construct('Компания уже активирована');
    }

}
