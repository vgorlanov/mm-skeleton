<?php

declare(strict_types=1);

namespace Common\Exceptions\Api;

use Throwable;

final class ApiNotFoundException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}