<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Unit\Middleware;

use PHPUnit\Framework\TestCase;
use Skyld\OatAssignment\Middleware\CorsMiddleware;
use Slim\Http\Request;
use Slim\Http\Response;

class CorsMiddlewareTest extends TestCase
{
    public function testItSetsResponseHeaders(): void
    {
        $next = function (Request $request, Response $response) {
            return $response;
        };

        $request = $this->createMock(Request::class);
        /** @var Response $response */
        $response = call_user_func(new CorsMiddleware(), $request, new Response(), $next);

        $this->assertEquals('*', $response->getHeaderLine('Access-Control-Allow-Origin'));
        $this->assertEquals(
            'X-Requested-With, Content-Type, Accept, Origin, Authorization',
            $response->getHeaderLine('Access-Control-Allow-Headers')
        );
        $this->assertEquals('GET', $response->getHeaderLine('Access-Control-Allow-Methods'));
    }
}
