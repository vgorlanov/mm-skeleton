<?php

declare(strict_types=1);


namespace Gateway\Http\Controllers\Order;


use Common\Uuid\Uuid;
use Gateway\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Order\API\Info\GetList\GetList;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;
use Illuminate\Http\JsonResponse;

#[Prefix('v1/order')]
final class ListController extends Controller
{
    private const string URI = '/list/{uuid?}';

    public function __construct(
        private readonly GetList $api
    ) {}

    /**
     * @param Request $request
     * @param non-empty-string|null $uuid
     * @return JsonResponse
     */
    #[Get(uri: self::URI, name: 'order.list')]
    public function __invoke(Request $request, string $uuid = null): JsonResponse
    {
        $page = $request->has('page') ? (int)$request->get('page') : 1;
        $perPage = $request->has('per_page') ? (int)$request->get('per_page') : 10;

        try {
            $uuid = $uuid !== null ? new Uuid($uuid) : null;
            return response()->json($this->api->list($perPage, $page, $uuid));
        } catch (\Throwable $e) {
            abort(404, $e->getMessage());
        }
    }

}
