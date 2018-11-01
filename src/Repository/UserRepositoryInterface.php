<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Repository;

use Skyld\OatAssignment\Domain\User\Model\User;
use Skyld\OatAssignment\Domain\User\Model\UserCollection;

interface UserRepositoryInterface
{
    public function findById(string $id): User;

    public function findAll(): UserCollection;
}
