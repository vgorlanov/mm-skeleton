<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\Product\Admin;

use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Illuminate\Http\JsonResponse;
use Modules\Product\API\GetById\GetById;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(AdminController::PREFIX)]
final class GetByIdController extends AdminController
{
    private const URI = '/product/{uuid}';

    public function __construct(
        private readonly GetById $api,
    ) {}

    #[Get(uri: self::URI, name: 'admin.product.show')]
    #[OAT\Get(
        path: self::PREFIX . self::URI,
        summary: 'Продукт компании',
        tags: ['Product (admin)'],
        parameters: [
            new OAT\Parameter(
                name: 'uuid',
                description: 'ID продукта',
                in: 'path',
                required: true,
                content: new OAT\JsonContent(type: 'string'),
            ),
        ],
        responses: [
            new Response(response: 200, description: 'Success'),
            new Response(response: 404, description: 'Product not found'),
        ],
    )]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            $product = $this->api->get(new Uuid($uuid));

            return response()->json($product);
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }

}
