<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Unit\Domain\User\Mapper;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Skyld\OatAssignment\Domain\User\Mapper\UserMapper;

class UserMapperTest extends TestCase
{
    /** @var UserMapper */
    private $subject;

    protected function setUp()
    {
        parent::setUp();

        $this->subject = new UserMapper();
    }

    public function testItCanMap(): void
    {
        $rawUsers = [
            'login' => 'testLogin',
            'password' => 'testPassword',
            'title' => 'testTitle',
            'firstname' => 'testFirstname',
            'lastname' => 'testLastname',
            'gender' => 'testGender',
            'email' => 'testEmail',
            'picture' => 'testPicture',
            'address' => 'testAddress',
        ];

        $user = $this->subject->map($rawUsers);

        $this->assertEquals('testLogin', $user->getLogin());
        $this->assertEquals('testPassword', $user->getPassword());
        $this->assertEquals('testTitle', $user->getTitle());
        $this->assertEquals('testFirstname', $user->getFirstname());
        $this->assertEquals('testLastname', $user->getLastname());
        $this->assertEquals('testGender', $user->getGender());
        $this->assertEquals('testEmail', $user->getEmail());
        $this->assertEquals('testPicture', $user->getPicture());
        $this->assertEquals('testAddress', $user->getAddress());
    }

    /**
     * @dataProvider provideRawUserWithMissingKey
     */
    public function testItThrowsExceptionIfKeyIsMissing(array $rawUser, string $expectedExceptionMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $this->subject->map($rawUser);
    }

    public function provideRawUserWithMissingKey(): array
    {
        return [
            'missingLogin' => [
                'rawUser' => [],
                'expectedExceptionMessage' => "Missing 'login' key while mapping user.",
            ],
            'missingPassword' => [
                'rawUser' => [
                    'login' => 'testLogin',

                ],
                'expectedExceptionMessage' => "Missing 'password' key while mapping user.",
            ],
            'missingTitle' => [
                'rawUser' => [
                    'login' => 'testLogin',
                    'password' => 'password',
                ],
                'expectedExceptionMessage' => "Missing 'title' key while mapping user.",
            ],
            'missingFirstname' => [
                'rawUser' => [
                    'login' => 'testLogin',
                    'password' => 'testPassword',
                    'title' => 'testTitle',
                ],
                'expectedExceptionMessage' => "Missing 'firstname' key while mapping user.",
            ],
            'missingLastname' => [
                'rawUser' => [
                    'login' => 'testLogin',
                    'password' => 'testPassword',
                    'title' => 'testTitle',
                    'firstname' => 'testFirstname',
                ],
                'expectedExceptionMessage' => "Missing 'lastname' key while mapping user.",
            ],
            'missingGender' => [
                'rawUser' => [
                    'login' => 'testLogin',
                    'password' => 'testPassword',
                    'title' => 'testTitle',
                    'firstname' => 'testFirstname',
                    'lastname' => 'testLastname',
                ],
                'expectedExceptionMessage' => "Missing 'gender' key while mapping user.",
            ],
            'missingEmail' => [
                'rawUser' => [
                    'login' => 'testLogin',
                    'password' => 'testPassword',
                    'title' => 'testTitle',
                    'firstname' => 'testFirstname',
                    'lastname' => 'testLastname',
                    'gender' => 'testGender',
                ],
                'expectedExceptionMessage' => "Missing 'email' key while mapping user.",
            ],
            'missingPicture' => [
                'rawUser' => [
                    'login' => 'testLogin',
                    'password' => 'testPassword',
                    'title' => 'testTitle',
                    'firstname' => 'testFirstname',
                    'lastname' => 'testLastname',
                    'gender' => 'testGender',
                    'email' => 'testEmail',
                ],
                'expectedExceptionMessage' => "Missing 'picture' key while mapping user.",
            ],
            'missingAddress' => [
                'rawUser' => [
                    'login' => 'testLogin',
                    'password' => 'testPassword',
                    'title' => 'testTitle',
                    'firstname' => 'testFirstname',
                    'lastname' => 'testLastname',
                    'gender' => 'testGender',
                    'email' => 'testEmail',
                    'picture' => 'testPicture',
                ],
                'expectedExceptionMessage' => "Missing 'address' key while mapping user.",
            ]
        ];
    }
}
