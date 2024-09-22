<?php

declare(strict_types=1);


namespace Gateway\Http\Controllers\Order;

use Gateway\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Order\API\System\Create\Create;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('v1/order')]
final class CreateController extends Controller
{
    private const URI = '/{uuid}';

    public function __construct(
       private readonly Create $api
    ) {}


    #[Post(uri: self::URI, name: 'order.create')]
    public function __invoke(Request $request): JsonResponse
    {
        $this->api->create($request);

        return response()->json('Created', 201);
    }
}
