<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Unit\Responder;

use League\Fractal\Manager;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Scope;
use PHPUnit\Framework\TestCase;
use Skyld\OatAssignment\Responder\Responder;
use Slim\Http\Response;

class ResponderTest extends TestCase
{
    /** @var Responder */
    private $subject;

    /** @var Manager */
    private $fractal;

    protected function setUp()
    {
        parent::setUp();

        $this->fractal = $this->createMock(Manager::class);
        $this->subject = new Responder($this->fractal);
    }

    public function testItCanCreateJsonResponse(): void
    {
        $response = $this->createMock(Response::class);
        $resource = $this->createMock(ResourceInterface::class);
        $scope = $this->createMock(Scope::class);

        $this->fractal
            ->expects($this->once())
            ->method('createData')
            ->with($resource)
            ->willReturn($scope);

        $scope
            ->expects($this->once())
            ->method('toArray')
            ->willReturn(['createdResource']);

        $response
            ->expects($this->once())
            ->method('withJson')
            ->with(['createdResource'], 200)
            ->willReturnSelf();

        $this->assertEquals($response, $this->subject->create($response, $resource, 200));
    }
}
