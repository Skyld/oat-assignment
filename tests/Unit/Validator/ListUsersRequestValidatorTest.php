<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Unit\Validator;

use PHPUnit\Framework\TestCase;
use Skyld\OatAssignment\Validator\ListUsersRequestValidator;
use Slim\Http\Environment;
use Slim\Http\Request;

class ListUsersRequestValidatorTest extends TestCase
{
    /** @var ListUsersRequestValidator */
    private $subject;

    protected function setUp()
    {
        parent::setUp();

        $this->subject = new ListUsersRequestValidator();
    }

    public function testItReturnsWithValidatedRequestParameters(): void
    {
        $request = Request::createFromEnvironment(Environment::mock());
        $request = $request->withQueryParams([
            'offset' => '99',
            'limit' => '10',
            'search' => 'test'
        ]);

        $this->assertEquals([
            'offset' => 99,
            'limit' => 10,
            'search' => 'test'
        ], $this->subject->validate($request));
    }

    /**
     * @expectedException \Skyld\OatAssignment\Exception\BadRequestException
     */
    public function testItThrowsBadRequestExceptionIfInvalidParametersReceived(): void
    {
        $this->expectExceptionMessage(<<<EOT
- All of the required rules must pass for { "offset": "invalid", "limit": "101", "search": "#$%" }
  - limit must be less than or equal to 100
  - offset must be an integer number
  - search must contain only letters (a-z) and digits (0-9)
EOT
        );

        $request = Request::createFromEnvironment(Environment::mock());
        $request = $request->withQueryParams([
            'offset' => 'invalid',
            'limit' => '101',
            'search' => '#$%'
        ]);

        $this->subject->validate($request);
    }
}
