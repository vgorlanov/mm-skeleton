<?php

declare(strict_types=1);

namespace Modules\Product\API\Update;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiValidationException;
use Common\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Product\Domain\services\dto\ProductDto;
use Modules\Product\Domain\services\UpdateService;
use Modules\Product\Infrastructure\RepositoryProductNotFoundException;
use Modules\Product\Validators\ProductValidator;

/**
 * @phpstan-import-type ProductRequest from ProductDto
 */
final class Module implements Update
{
    public function __construct(
        private UpdateService $service,
    ) {}

    /**
     * @param Uuid $uuid
     * @param Request $request
     * @return true
     * @throws ApiNotFoundException
     * @throws ApiValidationException
     * @throws \Modules\Product\Infrastructure\RepositoryProductAlreadyExistsException
     */
    public function update(Uuid $uuid, Request $request): true
    {
        try {
            (new ProductValidator())->validate($request);

            /** @var ProductRequest $params */
            $params = $request->all();
            $dto = ProductDto::make($params);

            $this->service->execute($uuid, $dto);
        } catch (ValidationException $e) {
            throw new ApiValidationException($e->getMessage(), $e->errors());
        } catch (RepositoryProductNotFoundException $e) {
            throw new ApiNotFoundException($e->getMessage());
        }

        return true;
    }
}
