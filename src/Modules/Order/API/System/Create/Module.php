<?php

declare(strict_types=1);


namespace Modules\Order\API\System\Create;


use Common\Uuid\Uuid;
use Illuminate\Http\Request;
use Modules\Order\Domain\services\dto\CustomerDto;
use Modules\Order\Domain\services\dto\OrderDto;
use Modules\Order\Domain\services\dto\ProductDto;
use Modules\User\API\Info\GetById\GetById;

final readonly class Module implements Create
{
    public function __construct(
        private \Modules\Order\Domain\services\Create $service,
        private GetById $userApi,
    ) {}


    public function create(Request $request): true
    {
        // todo validation

        $customerUuid = new Uuid($request->get('customer'));
        $user = $this->userApi->get($customerUuid);

        $dto = new OrderDto(
            company: new Uuid($request->get('company')),
            customer: new CustomerDto(
                uuid: $customerUuid,
                name: $user['data']->name . ' ' . $user['data']->surname,
                email: $user['credential']->email
            ),
            products: $this->makeProductsDto($request->get('products'))
        );

        $this->service->execute($dto);

        return true;
    }

    private function makeProductsDto(array $products): array
    {
        return array_map(static fn($item) => new ProductDto(
            uuid: new Uuid($item['uuid']),
            name: $item['name'],
            price: $item['price'],
            quantity: $item['quantity']
        ), $products);
    }
}
