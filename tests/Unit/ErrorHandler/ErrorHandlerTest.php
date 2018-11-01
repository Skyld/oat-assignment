<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Unit\ErrorHandler;

use Exception;
use PHPUnit\Framework\TestCase;
use Skyld\OatAssignment\ErrorHandler\ErrorHandler;
use Skyld\OatAssignment\Exception\BadRequestException;
use Skyld\OatAssignment\Exception\NotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;

class ErrorHandlerTest extends TestCase
{
    public function testItHandlesNotFoundException(): void
    {
        $request = $this->createMock(Request::class);
        /** @var Response $response */
        $response = call_user_func(new ErrorHandler(true), $request, new Response(), new NotFoundException('test'));

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['error' => 'test'], $this->getDecodedResponse($response));
    }

    public function testItHandlesBadRequestException(): void
    {
        $request = $this->createMock(Request::class);
        /** @var Response $response */
        $response = call_user_func(new ErrorHandler(true), $request, new Response(), new BadRequestException('test'));

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(['error' => 'test'], $this->getDecodedResponse($response));
    }

    public function testItHandlesAnyTypeOfExceptions(): void
    {
        $request = $this->createMock(Request::class);
        /** @var Response $response */
        $response = call_user_func(new ErrorHandler(true), $request, new Response(), new Exception('test'));

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(['error' => 'test'], $this->getDecodedResponse($response));
    }

    public function testItHidesInternalExceptionMessageIfSettingIsDisabled(): void
    {
        $request = $this->createMock(Request::class);
        /** @var Response $response */
        $response = call_user_func(new ErrorHandler(false), $request, new Response(), new Exception('test'));

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(['error' => 'Internal server error'], $this->getDecodedResponse($response));
    }

    private function getDecodedResponse(Response $response): array
    {
        return json_decode((string)$response->getBody(), true);
    }
}
