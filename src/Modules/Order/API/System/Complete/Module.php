<?php

declare(strict_types=1);


namespace Modules\Order\API\System\Complete;


use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Modules\Order\Domain\services\dto\CompleteDto;
use Modules\Order\Exceptions\OrderAlreadyCompleteException;
use Modules\Order\Exceptions\RepositoryOrderNotFoundException;

final class Module implements Complete
{
    public function __construct(
        private \Modules\Order\Domain\services\Complete $service
    ) {}


    public function complete(Uuid $uuid): true
    {
        try {
            $dto = new CompleteDto($uuid, new \DateTimeImmutable());
            $this->service->execute($dto);
        } catch (RepositoryOrderNotFoundException $e)  {
            throw new ApiNotFoundException($e->getMessage());
        } catch (OrderAlreadyCompleteException $e) {
            throw new ApiResponseException($e->getMessage());
        }

        return true;
    }
}
