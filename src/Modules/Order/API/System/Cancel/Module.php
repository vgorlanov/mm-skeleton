<?php

declare(strict_types=1);


namespace Modules\Order\API\System\Cancel;


use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\Order\Domain\services\dto\CancelDto;
use Modules\Order\Exceptions\OrderAlreadyCanceledException;
use Modules\Order\Exceptions\RepositoryOrderNotFoundException;

final class Module implements Cancel
{
    public function __construct(
        private \Modules\Order\Domain\services\Cancel $service
    ){}

    public function cancel(Uuid $uuid): true
    {
        try {
            $dto = new  CancelDto($uuid, new  \DateTimeImmutable());
            $this->service->execute($dto);
        } catch (RepositoryOrderNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        } catch (OrderAlreadyCanceledException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
