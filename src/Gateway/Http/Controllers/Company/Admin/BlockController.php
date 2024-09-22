<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\Company\Admin;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiResponseException;
use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Illuminate\Http\JsonResponse;
use Modules\Company\API\Block\Block;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(AdminController::PREFIX)]
final class BlockController extends AdminController
{
    private const URI = '/company/{uuid}/block';

    public function __construct(
        private readonly Block $block,
    ) {}

    #[Patch(uri: self::URI, name: 'admin.company.block')]
    #[OAT\Patch(
        path: self::PREFIX . self::URI,
        summary: 'Блокировка компании',
        tags: ['Company (admin)'],
        parameters: [
            new OAT\Parameter(
                name: 'uuid',
                description: 'ID компании',
                in: 'path',
                required: true,
                content: new OAT\JsonContent(type: 'string'),
            ),
        ],
        responses: [
            new Response(response: 200, description: 'Blocked'),
            new Response(response: 404, description: 'Company not found'),
            new Response(response: 422, description: 'Company is blocked'),
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
