<?php

declare(strict_types=1);


namespace Gateway\Http\Controllers\Order\Admin;


use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Gateway\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Order\API\System\Complete\Complete;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(AdminController::PREFIX)]
final class CompleteController extends Controller
{
    private const string URI = '/order/{uuid}/complete';

    public function __construct(
        private readonly Complete  $api
    ) {}

    #[Patch(uri: self::URI, name: 'admin.order.complete')]
    public function __invoke(string $uuid):  JsonResponse
    {
        try  {
            $this->api->complete(new Uuid($uuid));
        } catch (ApiResponseException $e) {
            return response()->json($e->getMessage(), 422);
        } catch (ApiNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json('Completed');
    }


}
