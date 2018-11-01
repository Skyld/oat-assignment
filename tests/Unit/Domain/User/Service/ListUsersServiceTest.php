<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Unit\Domain\User\Service;

use LimitIterator;
use PHPUnit\Framework\TestCase;
use Skyld\OatAssignment\Domain\User\Model\User;
use Skyld\OatAssignment\Domain\User\Model\UserCollection;
use Skyld\OatAssignment\Domain\User\Service\ListUserService;
use Skyld\OatAssignment\Repository\UserRepositoryInterface;

class ListUsersServiceTest extends TestCase
{
    /** @var ListUserService */
    private $subject;

    /** @var UserRepositoryInterface */
    private $repository;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->subject = new ListUserService($this->repository);
    }

    public function testItReturnsUserCollectionFromRepository(): void
    {
        $expectedCollection = new UserCollection();
        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedCollection);

        $this->assertEquals($expectedCollection, $this->subject->get('', 0, 10));
    }

    public function testItCanFindUserBySearchTerm(): void
    {
        $userToFind = $this->createUserWithLogin('userToFind');
        $expectedCollection = new UserCollection();
        $expectedCollection->add($userToFind);

        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedCollection);

        $collection = $this->subject->get('user', 0, 0);

        $this->assertCount(1, $collection);
        foreach ($collection as $user) {
            $this->assertEquals($userToFind, $user);
        }
    }

    public function testItCanFilterCollectionBasedOnSearchTerm(): void
    {
        $expectedCollection = new UserCollection();
        $expectedCollection->add($this->createUserWithLogin('login'));

        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedCollection);

        $collection = $this->subject->get('findMe', 0, 0);

        $this->assertEmpty($collection);
    }

    public function testItCanPaginate(): void
    {
        $collection = new UserCollection();
        for ($i=0; $i<20; $i++) {
            $collection->add($this->createUserWithLogin('login' . $i));
        }

        $this->repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($collection);

        $collection = $this->subject->get('', 1, 8);

        $this->assertInstanceOf(LimitIterator::class, $collection->getIterator());
        $numberOfItems = 0;
        $expectedLoginPrefix = 1;
        /** @var User $user */
        foreach ($collection as $user) {
            $this->assertEquals('login' . $expectedLoginPrefix, $user->getLogin());

            $expectedLoginPrefix++;
            $numberOfItems++;
        }

        $this->assertEquals(8, $numberOfItems);
    }

    private function createUserWithLogin(string $login): User
    {
        return new User(
            $login,
            'password',
            'title',
            'firstname',
            'lastname',
            'gender',
            'email',
            'picture',
            'address'
        );
    }
}
