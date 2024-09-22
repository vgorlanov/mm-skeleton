<?php

declare(strict_types=1);


namespace Gateway\Http\Controllers\Order\Admin;


use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Gateway\Http\Controllers\Controller;
use Modules\Order\API\System\Cancel\Cancel;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;
use Illuminate\Http\JsonResponse;

#[Prefix(AdminController::PREFIX)]
final class CancelController extends Controller
{
    private const string URI = '/order/{uuid}/cancel';

    public function __construct(
        private readonly Cancel $api
    ) {}

    #[Patch(uri: self::URI, name: 'admin.order.cancel')]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            $this->api->cancel(new Uuid($uuid));
        } catch (ApiResponseException $e) {
            return response()->json($e->getMessage(), 422);
        } catch (ApiNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json('Canceled');
    }


}
