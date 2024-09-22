<?php

declare(strict_types=1);


namespace Modules\Order\API\System\Activate;


use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\Order\Domain\services\dto\ActivateDto;
use Modules\Order\Exceptions\OrderAlreadyActivatedException;
use Modules\Order\Exceptions\RepositoryOrderNotFoundException;

final class Module implements Activate
{
    public function __construct(
        private \Modules\Order\Domain\services\Activate $service
    ) {}

    /**
     * @param Uuid $uuid
     * @return true
     * @throws ApiNotFoundException
     * @throws ApiResponseException
     */
    public function activate(Uuid $uuid): true
    {
        try {
            $dto = new ActivateDto($uuid, new  \DateTimeImmutable());
            $this->service->execute($dto);
        } catch (RepositoryOrderNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (OrderAlreadyActivatedException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
