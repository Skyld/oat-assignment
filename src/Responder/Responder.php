<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Responder;

use League\Fractal\Manager;
use League\Fractal\Resource\ResourceInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;

class Responder
{
    /** @var Manager */
    private $fractal;

    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;
    }

    public function create(
        Response $response,
        ResourceInterface $resource,
        int $statusCode = 200
    ): ResponseInterface {
        return $response->withJson($this->fractal->createData($resource)->toArray(), $statusCode);
    }
}
