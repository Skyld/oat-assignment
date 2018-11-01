<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Functional\Action;

class GetUserActionTest extends FunctionalTestCase
{
    public function testItReturnUserById(): void
    {
        $fixture = $this->createUserFixture('testUser');

        $response = $this->runApp('GET', '/users/' . $fixture->getLogin());

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals([
            'login' => $fixture->getLogin(),
            'title'=> ucfirst($fixture->getTitle()),
            'firstname'=> ucfirst($fixture->getFirstname()),
            'lastname'=> ucfirst($fixture->getLastname()),
            'gender'=> $fixture->getGender(),
            'email'=> $fixture->getEmail(),
            'picture'=> $fixture->getPicture(),
            'address'=> $fixture->getAddress()
        ], $this->getResponseBody($response));
    }

    public function testItReturns404IfUserCannotBeFoundById(): void
    {
        $response = $this->runApp('GET', '/users/unknown');

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertEquals(
            ['error' => 'User with id = unknown cannot be found.'],
            $this->getResponseBody($response)
        );
    }
}
