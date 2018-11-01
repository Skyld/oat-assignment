<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Domain\User\Service;

use Skyld\OatAssignment\Domain\User\Model\User;
use Skyld\OatAssignment\Domain\User\Model\UserCollection;
use Skyld\OatAssignment\Repository\UserRepositoryInterface;

class ListUserService
{
    private const DEFAULT_LIMIT = 10;

    /** @var UserRepositoryInterface */
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function get(string $searchTerm, int $offset, int $limit): UserCollection
    {
        $collection = $this->repository->findAll();

        if (!empty($searchTerm)) {
            $this->applySearchTerm($collection, $searchTerm);
        }

        return $collection
            ->setOffset($offset)
            ->setLimit(empty($limit) ? self::DEFAULT_LIMIT : $limit);
    }

    private function applySearchTerm(UserCollection $collection, string $searchTerm): void
    {
        /** @var User $user */
        foreach ($collection as $user) {
            if (strpos($user->getLogin(), $searchTerm) === false) {
                $collection->remove($user);
            }
        }
    }
}
