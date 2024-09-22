<?php

declare(strict_types=1);

namespace Common\Exceptions\Api;

use Throwable;

final class ApiValidationException extends \Exception
{
    /**
     * @param string $message
     * @param array<mixed> $failed
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message,
        private array $failed,
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array<mixed>
     */
    public function getFailed(): array
    {
        return [
            'message' => $this->message,
            'errors'  => $this->failed,
        ];
    }

}
