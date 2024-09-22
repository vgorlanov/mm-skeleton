<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\Product\Admin;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Illuminate\Http\JsonResponse;
use Modules\Product\API\Publish\Publish;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(AdminController::PREFIX)]
final class PublishController extends AdminController
{
    private const URI = '/product/{uuid}/publish';

    public function __construct(
        private readonly Publish $api,
    ) {}

    #[Patch(uri: self::URI, name: 'admin.product.publish')]
    #[OAT\Patch(
        path: self::PREFIX . self::URI,
        summary: 'Опубликовать продукт',
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
            new Response(response: 200, description: 'Published'),
            new Response(response: 404, description: 'Product not found'),
            new Response(response: 422, description: 'Product already published'),
        ],
    )]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            $this->api->publish(new Uuid($uuid));
        } catch (ApiResponseException $e) {
            return response()->json($e->getMessage(), 422);
        } catch (ApiNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json('Published');
    }
}
