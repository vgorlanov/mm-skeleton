<?php

declare(strict_types=1);

namespace Modules\Company\Infrastructure\Exceptions;

use Common\Uuid\Uuid;

final class RepositoryCompanyExistsException extends \LogicException
{
    public function __construct(Uuid $uuid)
    {
        parent::__construct('Компания ' . $uuid->toString() . ' уже существует');
    }
}
