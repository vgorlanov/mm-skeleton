<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\Company\Admin;

use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Illuminate\Http\JsonResponse;
use Modules\Company\API\GetById\GetById;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix(AdminController::PREFIX)]
final class GetByIdController extends AdminController
{
    private const string URI = '/company/{uuid}';

    public function __construct(
        private readonly GetById $get,
    ) {}

    #[Get(uri: self::URI, name: 'admin.company.show')]
    #[OAT\Get(
        path: self::PREFIX . self::URI,
        summary: 'Компания',
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
            new Response(
                response: 200,
                description: 'Success',
                content: new JsonContent(
                    properties: [
                        new OAT\Property(property: 'about', ref: '#/components/schemas/AboutDto'),
                        new OAT\Property(property: 'contacts', ref: '#/components/schemas/ContactsDto'),
                        // todo добавить информацию и статус
                    ],
                ),
            ),
            new Response(response: 404, description: 'Company not found'),
        ],
    )]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            return response()->json($this->get->get(new Uuid($uuid)));
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }
}
