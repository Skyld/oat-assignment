<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Action;

use League\Fractal\Resource\Collection as ResourceCollection;
use Psr\Http\Message\ResponseInterface;
use Skyld\OatAssignment\Domain\User\Service\ListUserService;
use Skyld\OatAssignment\Exception\BadRequestException;
use Skyld\OatAssignment\Responder\Responder;
use Skyld\OatAssignment\Transformer\UserCollectionTransformer;
use Skyld\OatAssignment\Validator\ListUsersRequestValidator;
use Slim\Http\Request;
use Slim\Http\Response;

class ListUsersAction
{
    /** @var ListUsersRequestValidator */
    private $requestValidator;

    /** @var ListUserService */
    private $service;

    /** @var UserCollectionTransformer */
    private $transformer;

    /** @var Responder */
    private $responder;

    public function __construct(
        ListUsersRequestValidator $requestValidator,
        ListUserService $service,
        UserCollectionTransformer $transformer,
        Responder $responder
    ) {
        $this->requestValidator = $requestValidator;
        $this->service = $service;
        $this->transformer = $transformer;
        $this->responder = $responder;
    }

    /**
     * @throws BadRequestException
     */
    public function __invoke(Request $request, Response $response): ResponseInterface
    {
        $validatedRequest = $this->requestValidator->validate($request);

        $collection = $this->service->get(
            $validatedRequest['search'],
            $validatedRequest['offset'],
            $validatedRequest['limit']
        );

        $resourceCollection = new ResourceCollection($collection, $this->transformer);

        return $this->responder->create($response, $resourceCollection);
    }
}
