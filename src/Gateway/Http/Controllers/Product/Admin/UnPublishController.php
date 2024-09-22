<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\Product\Admin;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Illuminate\Http\JsonResponse;
use Modules\Product\API\UnPublish\UnPublish;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(AdminController::PREFIX)]
final class UnPublishController extends AdminController
{
    private const URI = '/product/{uuid}/unpublish';

    public function __construct(
        private readonly UnPublish $api,
    ) {}

    #[Patch(uri: self::URI, name: 'admin.product.unpublish')]
    #[OAT\Patch(
        path: self::PREFIX . self::URI,
        summary: 'Снять продукт с публикации',
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
            new Response(response: 200, description: 'UnPublished'),
            new Response(response: 404, description: 'Product not found'),
            new Response(response: 422, description: 'Product not published'),
        ],
    )]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            $this->api->unPublish(new Uuid($uuid));
        } catch (ApiResponseException $e) {
            return response()->json($e->getMessage(), 422);
        } catch (ApiNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json('UnPublished');
    }


}
