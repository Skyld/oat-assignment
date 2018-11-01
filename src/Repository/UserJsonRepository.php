<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Repository;

use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Skyld\OatAssignment\Domain\User\Exception\UserNotFoundException;
use Skyld\OatAssignment\Domain\User\Mapper\UserMapper;
use Skyld\OatAssignment\Domain\User\Model\User;
use Skyld\OatAssignment\Domain\User\Model\UserCollection;

class UserJsonRepository implements UserRepositoryInterface
{
    /** @var FilesystemInterface */
    private $filesystem;

    /** @var string */
    private $filePath;

    /** @var UserMapper */
    private $mapper;

    public function __construct(FilesystemInterface $filesystem, UserMapper $mapper, string $filePath)
    {
        $this->filesystem = $filesystem;
        $this->mapper = $mapper;
        $this->filePath = $filePath;
    }

    /**
     * @inheritdoc
     *
     * @throws FileNotFoundException
     * @throws UserNotFoundException
     */
    public function findById(string $id): User
    {
        $user = $this->findAll()->getById($id);

        if (!$user instanceof User) {
            throw new UserNotFoundException(sprintf('User with id = %s cannot be found.', $id));
        }

        return $user;
    }

    /**
     * @inheritdoc
     *
     * @throws FileNotFoundException
     */
    public function findAll(): UserCollection
    {
        $userCollection = new UserCollection();
        $rawUserCollection = json_decode($this->filesystem->read($this->filePath), true);

        foreach ($rawUserCollection as $rawUser) {
            $userCollection->add($this->mapper->map($rawUser));
        }

        return $userCollection;
    }
}
