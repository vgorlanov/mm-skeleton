<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\User;

use Gateway\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\User\API\System\Create\Create;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(Controller::PREFIX)]
final class CreateController extends Controller
{
    private const string URI = '/user/create';

    public function __construct(
        private readonly Create $api,
    ) {}

    #[Post(uri: self::URI, name: 'user.create')]
    #[OAT\Post(
        path: self::PREFIX . self::URI,
        summary: 'Создание нового пользователя',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/UserDto',
            ),
        ),
        tags: ['User'],
        responses: [
            new Response(response: 201, description: 'Created'),
            new Response(response: 422, description: 'Validation'),
        ],
    )]
    public function __invoke(Request $request): JsonResponse
    {
        $this->api->create($request);

        return response()->json('User created', 201);
    }
}
