<?php

declare(strict_types=1);

namespace Modules\Product\API\Create;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Product\Domain\services\CreateService;
use Modules\Product\Domain\services\dto\ProductDto;
use Modules\Product\Infrastructure\RepositoryProductAlreadyExistsException;
use Modules\Product\Validators\ProductValidator;

/**
 * @phpstan-import-type ProductRequest from ProductDto
 */
final readonly class Module implements Create
{
    public function __construct(
        private CreateService $service,
    ) {}

    /**
     * @throws RepositoryProductAlreadyExistsException
     * @throws ValidationException
     */
    public function create(Request $request): true
    {
        (new ProductValidator())->validate($request);

        /** @var ProductRequest $params */
        $params = $request->all();
        $dto = ProductDto::make($params);

        $this->service->execute($dto);

        return true;
    }
}
