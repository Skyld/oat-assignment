<?php declare(strict_types=1);

namespace Skyld\OatAssignment\ErrorHandler;

use Psr\Http\Message\ResponseInterface;
use Skyld\OatAssignment\Exception\BadRequestException;
use Skyld\OatAssignment\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Throwable;

class ErrorHandler
{
    /** @var bool */
    private $displayErrorDetails;

    public function __construct(bool $displayErrorDetails)
    {
        $this->displayErrorDetails = $displayErrorDetails;
    }

    public function __invoke(
        Request $request,
        Response $response,
        Throwable $exception
    ): ResponseInterface {
        if ($exception instanceof NotFoundException) {
            return $response
                ->withStatus(404)
                ->withJson(['error' => $exception->getMessage()]);
        }

        if ($exception instanceof BadRequestException) {
            return $response
                ->withStatus(400)
                ->withJson(['error' => $exception->getMessage()]);
        }

        $message = $this->displayErrorDetails
            ? $exception->getMessage()
            : 'Internal server error';

        return $response
            ->withStatus(500)
            ->withJson(['error' => $message]);
    }
}
