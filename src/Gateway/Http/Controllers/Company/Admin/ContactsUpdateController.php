<?php

declare(strict_types=1);

namespace Gateway\Http\Controllers\Company\Admin;

use Common\Exceptions\Api\ApiNotFoundException;
use Common\Exceptions\Api\ApiValidationException;
use Common\Uuid\Uuid;
use Gateway\Http\Controllers\AdminController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Company\API\ContactsUpdate\ContactsUpdate;
use OpenApi\Attributes as OAT;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Put;

#[Prefix(AdminController::PREFIX)]
final class ContactsUpdateController extends AdminController
{
    private const URI = '/company/{uuid}/contacts/update';

    public function __construct(
        private readonly ContactsUpdate $update,
    ) {}

    #[Put(uri: self::URI, name: 'admin.company.contacts.update')]
    #[OAT\Put(
        path: self::PREFIX . self::URI,
        summary: 'Обновление контактов компании',
        requestBody: new RequestBody(
            required: true,
            content: new JsonContent(
                ref: '#/components/schemas/ContactsDto',
            ),
        ),
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
            new Response(response: 200, description: 'Contacts updated'),
            new Response(response: 404, description: 'Company not found'),
            new Response(response: 422, description: 'Validation'),
        ],
    )]
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        try {
            $this->update->update(new Uuid($uuid), $request);
        } catch (ApiValidationException $e) {
            return response()->json($e->getFailed(), 422);
        } catch (ApiNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

        return response()->json('Contacts updated');
    }
}
