<?php

declare(strict_types=1);

namespace Modules\User\API\System\DataUpdate;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiValidationException;
use Common\Uuid\Uuid;
use Illuminate\Http\Request;
use Modules\User\Domain\services\DataUpdateService;
use Modules\User\Domain\services\dto\DataDto;
use Modules\User\Infrastructure\RepositoryUserNotFoundException;

final readonly class Module implements DataUpdate
{
    public function __construct(
        private DataUpdateService $service,
    ) {}

    /**
     * @param Uuid $uuid
     * @param Request $request
     * @return true
     * @throws ApiNotFoundException
     * @throws ApiValidationException
     */
    public function update(Uuid $uuid, Request $request): true
    {
        try {
            $dto = DataDto::make($request->all()); //@phpstan-ignore-line

            $this->service->execute($uuid, $dto);
        } catch (RepositoryUserNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        }

        return true;
    }
}
