<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Unit\Domain\User\Exception;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Skyld\OatAssignment\Domain\User\Exception\UserNotFoundException;

class UserNotFoundExceptionTest extends TestCase
{
    public function testItIsRuntimeException(): void
    {
        $this->assertInstanceOf(RuntimeException::class, new UserNotFoundException());
    }
}
