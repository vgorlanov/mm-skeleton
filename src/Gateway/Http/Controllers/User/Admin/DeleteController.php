<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\User\Admin;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Illuminate\Http\JsonResponse;
use Modules\User\API\System\Delete\Delete;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes as SA;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(AdminController::PREFIX)]
final class DeleteController extends AdminController
{
    private const URI = '/user/{uuid}';

    public function __construct(
        private readonly Delete $delete,
    ) {}

    #[SA\Delete(uri: self::URI, name: 'admin.user.delete')]
    #[OAT\Delete(
        path: self::PREFIX . self::URI,
        summary: 'Удаление пользователя',
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
            new Response(response: 200, description: 'Deleted'),
            new Response(response: 404, description: 'User not found'),
            new Response(response: 422, description: 'User is deleted'),
        ],
    )]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            $this->delete->delete(new Uuid($uuid));
        } catch (ApiResponseException $e) {
            return response()->json($e->getMessage(), 422);
        } catch (ApiNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json('Deleted');
    }


}
