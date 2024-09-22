<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\User\Admin;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Illuminate\Http\JsonResponse;
use Modules\User\API\System\UnBlock\UnBlock;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(AdminController::PREFIX)]
final class UnBlockController extends AdminController
{
    private const URI = '/user/{uuid}/unblock';

    public function __construct(
        private readonly UnBlock $unBlock,
    ) {}

    #[Patch(uri: self::URI, name: 'admin.user.unblock')]
    #[OAT\Patch(
        path: self::PREFIX . self::URI,
        summary: 'Разблокировка пользователя',
        tags: ['User (admin)'],
        parameters: [
            new OAT\Parameter(
                name: 'uuid',
                description: 'ID пользователя',
                in: 'path',
                required: true,
                content: new OAT\JsonContent(type: 'string'),
            ),
        ],
        responses: [
            new Response(response: 200, description: 'UnBlocked'),
            new Response(response: 404, description: 'User not found'),
            new Response(response: 422, description: 'User is not blocked'),
        ],
    )]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            $this->unBlock->unblock(new Uuid($uuid));
        } catch (ApiResponseException $e) {
            return response()->json($e->getMessage(), 422);
        } catch (ApiNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json('Unblocked');
    }

}
