<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Functional\Action;

class ListUsersActionTest extends FunctionalTestCase
{
    public function testItReturnsListOfUsers(): void
    {
        $fixture = $this->createUserFixture();
        $fixture2 = $this->createUserFixture();

        $response = $this->runApp('GET', '/users');

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals([
            [
                'login' => $fixture->getLogin(),
                'title'=> ucfirst($fixture->getTitle()),
                'firstname'=> ucfirst($fixture->getFirstname()),
                'lastname'=> ucfirst($fixture->getLastname()),
                'gender'=> $fixture->getGender(),
                'email'=> $fixture->getEmail(),
                'picture'=> $fixture->getPicture(),
                'address'=> $fixture->getAddress()
            ],
            [
                'login' => $fixture2->getLogin(),
                'title'=> ucfirst($fixture2->getTitle()),
                'firstname'=> ucfirst($fixture2->getFirstname()),
                'lastname'=> ucfirst($fixture2->getLastname()),
                'gender'=> $fixture2->getGender(),
                'email'=> $fixture2->getEmail(),
                'picture'=> $fixture2->getPicture(),
                'address'=> $fixture2->getAddress()
            ]
        ], $this->getResponseBody($response));
    }

    public function testItCanApplySearchTerm(): void
    {
        $expectedFixture = $this->createUserFixture('johnDoe');
        $this->createUserFixture('janeDoe');

        $response = $this->runApp('GET', '/users?search=johnDoe');

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals([
            [
                'login' => 'johnDoe',
                'title'=> ucfirst($expectedFixture->getTitle()),
                'firstname'=> ucfirst($expectedFixture->getFirstname()),
                'lastname'=> ucfirst($expectedFixture->getLastname()),
                'gender'=> $expectedFixture->getGender(),
                'email'=> $expectedFixture->getEmail(),
                'picture'=> $expectedFixture->getPicture(),
                'address'=> $expectedFixture->getAddress()
            ],
        ], $this->getResponseBody($response));
    }

    public function testItThrowsBadRequestExceptionIfOffsetParameterIsNonNumeric(): void
    {
        $response = $this->runApp('GET', '/users?offset=invalid');

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(
            ['error' => '- offset must be an integer number'],
            $this->getResponseBody($response)
        );
    }

    public function testItThrowsBadRequestExceptionIfLimitParameterIsInvalid(): void
    {
        $response = $this->runApp('GET', '/users?limit=invalid');

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(
            [
                'error' => <<<EOT
- All of the required rules must pass for limit
  - limit must be an integer number
    - limit must be greater than or equal to 1
EOT
            ],
            $this->getResponseBody($response)
        );
    }

    public function testItThrowsBadRequestExceptionIfSearchParameterIsInvalid(): void
    {
        $response = $this->runApp('GET', '/users?search=^#$');

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(
            ['error' => '- search must contain only letters (a-z) and digits (0-9)'],
            $this->getResponseBody($response)
        );
    }
}
