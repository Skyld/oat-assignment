<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Domain\User\Mapper;

use InvalidArgumentException;
use Skyld\OatAssignment\Domain\User\Model\User;

class UserMapper
{
    /**
     * @throws InvalidArgumentException
     */
    public function map(array $rawUser): User
    {
        $this->checkIndexes([
            'login',
            'password',
            'title',
            'firstname',
            'lastname',
            'gender',
            'email',
            'picture',
            'address'
        ], $rawUser);

        return new User(
            $rawUser['login'],
            $rawUser['password'],
            $rawUser['title'],
            $rawUser['firstname'],
            $rawUser['lastname'],
            $rawUser['gender'],
            $rawUser['email'],
            $rawUser['picture'],
            $rawUser['address']
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    private function checkIndexes(array $expectedIndexes, array $rawUser): void
    {
        foreach ($expectedIndexes as $expectedIndex) {
            if (!isset($rawUser[$expectedIndex])) {
                throw new InvalidArgumentException(
                    sprintf(
                        "Missing '%s' key while mapping user.",
                        $expectedIndex
                    )
                );
            }
        }
    }
}
