<?php

declare(strict_types=1);


namespace Gateway\Http\Controllers\User;


use Common\Uuid\Uuid;
use Gateway\Http\Controllers\Controller;
use Modules\User\API\Info\GetById\GetById;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;
use Illuminate\Http\JsonResponse;

#[Prefix(Controller::PREFIX)]
final class GetByIdController extends Controller
{
    private const string URI = '/user/{uuid}';

    public function __construct(
        private readonly GetById $api
    ) {}


    #[Get(uri: self::URI, name: 'user.show')]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            return response()->json($this->api->get(new Uuid($uuid)));
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }
}
