<?php declare(strict_types=1);

namespace App\Tests\Functional;

use App\Handler\MappingTableHandler;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MappingTableHandlerTest extends KernelTestCase
{
    public const ARRAY_STRING  = ['1' => 'FOO', '2' => 'BAZ'];

    public const ARRAY_BOOLEAN  = ['1' => true, '2' => false];

    private MappingTableHandler $handler;

    private SerializationVisitorInterface $serializationVisitor;

    private DeserializationVisitorInterface $deserializationVisitor;

    private SerializationContext $context;

    protected function setUp(): void
    {
        $this->handler = new MappingTableHandler();
        $this->context = $this->getMockBuilder(SerializationContext::class)->getMock();
        $this->serializationVisitor = $this->getMockBuilder(SerializationVisitorInterface::class)->getMock();
        $this->deserializationVisitor = $this->getMockBuilder(DeserializationVisitorInterface::class)->getMock();
    }

    private function getType(string $mappingTable): array
    {
       return ['name' => 'MappingTable', 'params' => [$mappingTable]];
    }
    
    public function testMappingTableNotDefined()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->handler->serialize(
            $this->serializationVisitor,
            'BAZ',
            $this->getType(''),
            $this->context
        );
    }

    public function testSerializeArrayString()
    {
        $values = [
            json_encode(self::ARRAY_STRING),
            __CLASS__ . '::ARRAY_STRING'
        ];

        foreach ($values as $value) {
            $this->assertSame(
                '2',
                $this->handler->serialize(
                    $this->serializationVisitor,
                    'BAZ',
                    $this->getType($value),
                    $this->context
                )
            );
        }
    }

    public function testSerializeArrayBoolean()
    {
        $values = [
            json_encode(self::ARRAY_BOOLEAN),
            __CLASS__ . '::ARRAY_BOOLEAN'
        ];

        foreach ($values as $value) {
            $this->assertSame(
                '1',
                $this->handler->serialize(
                    $this->serializationVisitor,
                    true,
                    $this->getType($value),
                    $this->context
                )
            );
        }
    }

    public function testSerializeToEmptyString()
    {
        $this->assertSame(
            '',
            $this->handler->serialize(
                $this->serializationVisitor,
                null,
                $this->getType(__CLASS__ . '::ARRAY_STRING'),
                $this->context
            )
        );
    }

    public function testDeserializeArrayString()
    {
        $values = [
            json_encode(self::ARRAY_STRING),
            __CLASS__ . '::ARRAY_STRING'
        ];

        foreach ($values as $value) {
            $this->assertSame(
                'BAZ',
                $this->handler->deserialize(
                    $this->deserializationVisitor,
                    '2',
                    $this->getType($value)
                )
            );
        }
    }

    public function testDeserializeArrayBoolean()
    {
        $values = [
            json_encode(self::ARRAY_BOOLEAN),
            __CLASS__ . '::ARRAY_BOOLEAN'
        ];

        foreach ($values as $value) {
            $this->assertFalse(
                $this->handler->deserialize(
                    $this->deserializationVisitor,
                    '2',
                    $this->getType($value)
                )
            );
        }
    }

    public function testDeserializeNoMppped()
    {
        $values = [
            json_encode(self::ARRAY_STRING),
            __CLASS__ . '::ARRAY_STRING'
        ];

        foreach ($values as $value) {
            $this->assertNull(
                $this->handler->deserialize(
                    $this->deserializationVisitor,
                    '7',
                    $this->getType($value)
                )
            );
        }
    }
}
