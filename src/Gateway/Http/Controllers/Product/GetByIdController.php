<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\Product;

use Common\Uuid\Uuid;
use Gateway\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
//use Modules\Search\API\GetById\GetById;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('v1/product')]
final class GetByIdController extends Controller
{
    private const URI = '/{uuid}';

    private const INDEX = 'product';

    public function __construct(
//        private readonly GetById $api,
    ) {}

    #[Get(uri: self::URI, name: 'product.show')]
    #[OAT\Get(
        path: '/product/{uuid}',
        summary: 'Продукт',
        tags: ['Product'],
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
            return response()->json($this->api->get(self::INDEX, new Uuid($uuid)));
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }
}
