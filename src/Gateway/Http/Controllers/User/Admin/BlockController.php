<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\User\Admin;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Illuminate\Http\JsonResponse;
use Modules\User\API\System\Block\Block;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(AdminController::PREFIX)]
final class BlockController extends AdminController
{
    private const URI = '/user/{uuid}/block';

    public function __construct(
        private readonly Block $block,
    ) {}

    #[Patch(uri: self::URI, name: 'admin.user.block')]
    #[OAT\Patch(
        path: self::PREFIX . self::URI,
        summary: 'Блокировка пользователя',
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
            new Response(response: 200, description: 'Blocked'),
            new Response(response: 404, description: 'User not found'),
            new Response(response: 422, description: 'User is blocked'),
        ],
    )]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            $this->block->block(new Uuid($uuid));
        } catch (ApiResponseException $e) {
            return response()->json($e->getMessage(), 422);
        } catch (ApiNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        }

        return response()->json('Blocked');
    }
}
