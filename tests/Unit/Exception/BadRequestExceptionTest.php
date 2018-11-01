<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Skyld\OatAssignment\Exception\BadRequestException;

class BadRequestExceptionTest extends TestCase
{
    public function testItIsRuntimeException(): void
    {
        $this->assertInstanceOf(RuntimeException::class, new BadRequestException());
    }
}
