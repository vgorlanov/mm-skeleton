<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\Product\Admin;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiValidationException;
use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Product\API\Update\Update;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Put;

#[Prefix(AdminController::PREFIX)]
final class UpdateController extends AdminController
{
    private const URI = '/product/{uuid}';

    public function __construct(
        private readonly Update $api,
    ) {}

    #[Put(uri: self::URI, name: 'admin.product.update')]
    #[OAT\Put(
        path: self::PREFIX . self::URI,
        summary: 'Обновление продукта компании',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/ProductDto',
            ),
        ),
        tags: ['Product (admin)'],
        responses: [
            new Response(response: 200, description: 'Updated'),
            new Response(response: 422, description: 'Validation'),
        ],
    )]
    public function __invoke(Request $request, string $uuid): JsonResponse
    {
        try {
            $this->api->update(new Uuid($uuid), $request);
        } catch (ApiValidationException $e) {
            return response()->json($e->getFailed(), 422);
        } catch (ApiNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json('Updated');
    }
}
