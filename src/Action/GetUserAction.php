<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Action;

use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseInterface;
use Skyld\OatAssignment\Domain\User\Exception\UserNotFoundException;
use Skyld\OatAssignment\Exception\NotFoundException;
use Skyld\OatAssignment\Repository\UserRepositoryInterface;
use Skyld\OatAssignment\Responder\Responder;
use Skyld\OatAssignment\Transformer\UserCollectionTransformer;
use Slim\Http\Response;

class GetUserAction
{
    /** @var UserRepositoryInterface */
    private $repository;

    /** @var UserCollectionTransformer */
    private $transformer;

    /** @var Responder */
    private $responder;

    public function __construct(
        UserRepositoryInterface $repository,
        UserCollectionTransformer $transformer,
        Responder $responder
    ) {
        $this->repository = $repository;
        $this->transformer = $transformer;
        $this->responder = $responder;
    }

    /**
     * @throws NotFoundException
     */
    public function __invoke(Response $response, string $id): ResponseInterface
    {
        try {
            $resourceItem = new Item($this->repository->findById($id), $this->transformer);
        } catch (UserNotFoundException $exception) {
            throw new NotFoundException($exception->getMessage());
        }

        return $this->responder->create($response, $resourceItem);
    }
}
