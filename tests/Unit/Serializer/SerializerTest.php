<?php declare(strict_types=1);

namespace Skyld\OatAssignment\Tests\Unit\Serializer;

use League\Fractal\Serializer\DataArraySerializer;
use PHPUnit\Framework\TestCase;
use Skyld\OatAssignment\Serializer\Serializer;

class SerializerTest extends TestCase
{
    /** @var Serializer */
    private $subject;

    protected function setUp()
    {
        parent::setUp();

        $this->subject = new Serializer();
    }

    public function testItIsDataArraySerializer(): void
    {
        $this->assertInstanceOf(DataArraySerializer::class, $this->subject);
    }

    public function testItRemovesDataKeyWhenSerializingItems(): void
    {
        $this->assertEquals(['item'], $this->subject->item('', ['item']));
    }

    public function testItRemovesDataKeyWhenSerializingCollections(): void
    {
        $this->assertEquals(['collection'], $this->subject->collection('', ['collection']));
    }
}
