<?php

namespace Functional;

use App\Dto\ContactDto;
use App\Handler\MappingTableHandler;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Type\Parser;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ContactDtoTest extends TestCase
{
    public function testConstantsInJmsSerializerTypeAttributeExists()
    {
        $salutation = $this->getTypeAttribute('salutation');
        $this->assertTrue(defined($salutation['params'][0]));
        $this->assertSame(MappingTableHandler::HANDLER_TYPE, $salutation['name']);

        $salutation = $this->getTypeAttribute('marketingInformation');
        $this->assertTrue(defined($salutation['params'][0]));
        $this->assertSame(MappingTableHandler::HANDLER_TYPE, $salutation['name']);
    }

    private function getTypeAttribute(string $propertyName): array
    {
        $reflectionClass = new ReflectionClass(ContactDto::class);
        $reflectionProperty = $reflectionClass->getProperty($propertyName);
        $reflectionAttributes = $reflectionProperty->getAttributes(Type::class);
        $attributeArguments = $reflectionAttributes[0]->getArguments();
        $parser = new Parser();
        return $parser->parse($attributeArguments[0]);
    }

}
