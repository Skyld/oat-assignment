<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Validator;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use Skyld\OatAssignment\Exception\BadRequestException;
use Slim\Http\Request;

class ListUsersRequestValidator
{
    /**
     * @throws BadRequestException
     */
    public function validate(Request $request): array
    {
        try {
            v::allOf(
                v::key('limit', v::intVal()->between(1, 100)->setName('limit'), false),
                v::key('offset', v::intVal()->setName('offset'), false),
                v::key('search', v::optional(v::alnum())->setName('search'), false)
            )->assert($request->getQueryParams());
        } catch (NestedValidationException $exception) {
            throw new BadRequestException($exception->getFullMessage());
        }

        return [
            'limit' => (int)$request->getQueryParam('limit'),
            'offset' => (int)$request->getQueryParam('offset'),
            'search' => (string)$request->getQueryParam('search'),
        ];
    }
}
