<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Unit\Transformer;

use PHPUnit\Framework\TestCase;
use Skyld\OatAssignment\Domain\User\Model\User;
use Skyld\OatAssignment\Transformer\UserCollectionTransformer;

class UserCollectionTransformerTest extends TestCase
{
    /** @var UserCollectionTransformer */
    private $subject;

    protected function setUp()
    {
        parent::setUp();

        $this->subject = new UserCollectionTransformer();
    }

    public function testItCanTransform(): void
    {
        $user = new User(
            'login',
            'password',
            'title',
            'firstname',
            'lastname',
            'gender',
            'email',
            'picture',
            'address'
        );

        $this->assertEquals([
            'login' => 'login',
            'title' => 'Title',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'gender' => 'gender',
            'email' => 'email',
            'picture' => 'picture',
            'address' => 'address'
        ], call_user_func($this->subject, $user));
    }
}
