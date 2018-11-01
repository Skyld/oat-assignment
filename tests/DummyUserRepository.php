<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests;

use Skyld\OatAssignment\Domain\User\Exception\UserNotFoundException;
use Skyld\OatAssignment\Domain\User\Model\User;
use Skyld\OatAssignment\Domain\User\Model\UserCollection;
use Skyld\OatAssignment\Repository\UserRepositoryInterface;

class DummyUserRepository implements UserRepositoryInterface
{
    /** @var UserCollection */
    private $collection;

    public function __construct()
    {
        $this->collection = new UserCollection();
    }

    /**
     * @throws UserNotFoundException
     */
    public function findById(string $id): User
    {
        $user = $this->findAll()->getById($id);

        if (null === $user) {
            throw new UserNotFoundException(sprintf('User with id = %s cannot be found.', $id));
        }

        return $user;
    }

    public function findAll(): UserCollection
    {
        return $this->collection;
    }

    public function addFixture(User $user): self
    {
        $this->collection->add($user);

        return $this;
    }
}
