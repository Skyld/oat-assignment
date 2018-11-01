<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Integration\Repository;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Memory\MemoryAdapter;
use PHPUnit\Framework\TestCase;
use Skyld\OatAssignment\Domain\User\Mapper\UserMapper;
use Skyld\OatAssignment\Domain\User\Model\User;
use Skyld\OatAssignment\Repository\UserJsonRepository;
use Skyld\OatAssignment\Repository\UserRepositoryInterface;

class UserJsonRepositoryTest extends TestCase
{
    private const JSON_PATH = 'test.json';

    /** @var UserJsonRepository */
    private $subject;

    /** @var FilesystemInterface */
    private $filesystem;

    protected function setUp()
    {
        parent::setUp();

        $this->filesystem = new Filesystem(new MemoryAdapter());
        $this->subject = new UserJsonRepository($this->filesystem, new UserMapper(), self::JSON_PATH);
    }

    public function testItImplementsUserRepositoryInterface(): void
    {
        $this->assertInstanceOf(UserRepositoryInterface::class, $this->subject);
    }

    public function testItCanFindUserById(): void
    {
        $rawUserCollection = json_encode([
            [
                'login' => 'testLogin1',
                'password' => 'testPassword1',
                'title' => 'testTitle1',
                'firstname' => 'testFirstname1',
                'lastname' => 'testLastname1',
                'gender' => 'testGender1',
                'email' => 'testEmail1',
                'picture' => 'testPicture1',
                'address' => 'testAddress1',
            ]
        ]);

        $this->filesystem->write(self::JSON_PATH, $rawUserCollection);

        $expectedUser = new User(
            'testLogin1',
            'testPassword1',
            'testTitle1',
            'testFirstname1',
            'testLastname1',
            'testGender1',
            'testEmail1',
            'testPicture1',
            'testAddress1'
        );

        $this->assertEquals($expectedUser, $this->subject->findById('testLogin1'));
    }

    /**
     * @expectedException \Skyld\OatAssignment\Domain\User\Exception\UserNotFoundException
     * @expectedExceptionMessage User with id = invalidLogin cannot be found.
     */
    public function testItThrowsExceptionIfUserCannotBeFoundById(): void
    {
        $this->filesystem->write(self::JSON_PATH, json_encode([]));

        $this->subject->findById('invalidLogin');
    }

    public function testItCanFindAllUsers(): void
    {
        $rawUserCollection = json_encode([
            [
                'login' => 'testLogin1',
                'password' => 'testPassword1',
                'title' => 'testTitle1',
                'firstname' => 'testFirstname1',
                'lastname' => 'testLastname1',
                'gender' => 'testGender1',
                'email' => 'testEmail1',
                'picture' => 'testPicture1',
                'address' => 'testAddress1',
            ],
            [
                'login' => 'testLogin2',
                'password' => 'testPassword2',
                'title' => 'testTitle2',
                'firstname' => 'testFirstname2',
                'lastname' => 'testLastname2',
                'gender' => 'testGender2',
                'email' => 'testEmail2',
                'picture' => 'testPicture2',
                'address' => 'testAddress2',
            ]
        ]);

        $this->filesystem->write(self::JSON_PATH, $rawUserCollection);

        $expectedUser1 = new User(
            'testLogin1',
            'testPassword1',
            'testTitle1',
            'testFirstname1',
            'testLastname1',
            'testGender1',
            'testEmail1',
            'testPicture1',
            'testAddress1'
        );

        $expectedUser2 = new User(
            'testLogin2',
            'testPassword2',
            'testTitle2',
            'testFirstname2',
            'testLastname2',
            'testGender2',
            'testEmail2',
            'testPicture2',
            'testAddress2'
        );

        $collection = $this->subject->findAll();

        $this->assertCount(2, $collection);

        $this->assertEquals(
            [
            'testLogin1' => $expectedUser1,
            'testLogin2' => $expectedUser2
            ],
            $collection->getIterator()->getArrayCopy()
        );
    }
}
