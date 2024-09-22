<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\Product;

use Gateway\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
//use Modules\Search\API\ListByIds\ListByIds;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('v1/product')]
final class ListByIdsController extends Controller
{
    private const string URI = '/list/ids';

    private const string INDEX = 'product';

    public function __construct(
//        private readonly ListByIds $api,
    ) {}

    #[Get(uri: self::URI, name: 'product.list')]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            return response()->json($this->api->get(self::INDEX, $request->get('ids')));
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }

}
