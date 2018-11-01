<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Transformer;

use Skyld\OatAssignment\Domain\User\Model\User;

class UserCollectionTransformer
{
    public function __invoke(User $user): array
    {
        // We do not expose password

        return [
            'login' => $user->getLogin(),
            'title' => ucfirst($user->getTitle()),
            'firstname' => ucfirst($user->getFirstname()),
            'lastname' => ucfirst($user->getLastname()),
            'gender' => $user->getGender(),
            'email' => $user->getEmail(),
            'picture' => $user->getPicture(),
            'address' => $user->getAddress(),
        ];
    }
}
