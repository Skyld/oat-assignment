<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Integration\Repository;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Memory\MemoryAdapter;
use PHPUnit\Framework\TestCase;
use Skyld\OatAssignment\Domain\User\Mapper\UserMapper;
use Skyld\OatAssignment\Domain\User\Model\User;
use Skyld\OatAssignment\Repository\UserCsvRepository;
use Skyld\OatAssignment\Repository\UserRepositoryInterface;

class UserCsvRepositoryTest extends TestCase
{
    private const CSV_PATH = 'test.csv';

    /** @var UserCsvRepository */
    private $subject;

    /** @var FilesystemInterface */
    private $filesystem;

    protected function setUp()
    {
        parent::setUp();

        $this->filesystem = new Filesystem(new MemoryAdapter());
        $this->subject = new UserCsvRepository($this->filesystem, new UserMapper(), self::CSV_PATH);
    }

    public function testItImplementsUserRepositoryInterface(): void
    {
        $this->assertInstanceOf(UserRepositoryInterface::class, $this->subject);
    }

    public function testItCanFindUserById(): void
    {
        $this->filesystem->write(self::CSV_PATH, $this->getCsvContent());

        $expectedUser = new User(
            'testLogin',
            'testPassword',
            'testTitle',
            'testFirstname',
            'testLastname',
            'testGender',
            'testEmail',
            'testPicture',
            'testAddress'
        );

        $this->assertEquals($expectedUser, $this->subject->findById('testLogin'));
    }

    /**
     * @expectedException \Skyld\OatAssignment\Domain\User\Exception\UserNotFoundException
     * @expectedExceptionMessage User with id = invalidLogin cannot be found.
     */
    public function testItThrowsExceptionIfUserCannotBeFoundById(): void
    {
        $this->filesystem->write(self::CSV_PATH, $this->getCsvContent());

        $this->subject->findById('invalidLogin');
    }

    public function testItReturnsUserCollection(): void
    {
        $this->filesystem->write(self::CSV_PATH, $this->getCsvContent());

        $expectedUser = new User(
            'testLogin',
            'testPassword',
            'testTitle',
            'testFirstname',
            'testLastname',
            'testGender',
            'testEmail',
            'testPicture',
            'testAddress'
        );

        $collection = $this->subject->findAll();

        $this->assertCount(1, $collection);
        foreach ($collection->getIterator() as $user) {
            $this->assertEquals($expectedUser, $user);
        }
    }

    private function getCsvContent(): string
    {
        return <<<EOT
login,password,title,lastname,firstname,gender,email,picture,address
testLogin,testPassword,testTitle,testLastname,testFirstname,testGender,testEmail,testPicture,testAddress
EOT;
    }
}
