<?php

declare(strict_types=1);


namespace Gateway\Http\Controllers\Order\Admin;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Gateway\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Order\API\System\Activate\Activate;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(AdminController::PREFIX)]
final class ActivateController extends Controller
{
    private const string URI = '/order/{uuid}/activate';

    public function __construct(
        private readonly Activate $api
    ) {}

    #[Patch(uri: self::URI, name: 'admin.order.activate')]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            $this->api->activate(new Uuid($uuid));
        } catch (ApiResponseException $e) {
            return response()->json($e->getMessage(), 422);
        } catch (ApiNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json('Activated');
    }
}
