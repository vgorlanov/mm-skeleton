<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\Product\Admin;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Illuminate\Http\JsonResponse;
use Modules\Product\API\Restore\Restore;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(AdminController::PREFIX)]
final class RestoreController extends AdminController
{
    private const URI = '/product/{uuid}/restore';

    public function __construct(
        private readonly Restore $api,
    ) {}

    #[Patch(uri: self::URI, name: 'admin.product.restore')]
    #[OAT\Patch(
        path: self::PREFIX . self::URI,
        summary: 'Восстановить продукт',
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
            new Response(response: 200, description: 'Restored'),
            new Response(response: 404, description: 'Product not found'),
            new Response(response: 422, description: 'Product not deleted'),
        ],
    )]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            $this->api->restore(new Uuid($uuid));
        } catch (ApiResponseException $e) {
            return response()->json($e->getMessage(), 422);
        } catch (ApiNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json('Restored');
    }
}
