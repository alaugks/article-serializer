<?php

namespace App\Tests\Functional;

use App\Dto\ContactDto;
use App\Normalizer\Value\BooleanValue;
use App\Normalizer\Value\StringValue;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @SuppressWarnings("php:S1192")
 */
class NormalizeDenormalizeTest extends KernelTestCase
{
    private SerializerInterface $serializer;

    public array $contactDto = [
        "1" => "Jane",
        "2" => "Doe",
        "3" => "jane.doe@example.com",
        "4" => "1989-11-09",
        "46" =>"2",
        "100674" => "1"
    ];

    public function setUp(): void
    {
        self::bootKernel();
        $this->serializer = self::getContainer()->get(SerializerInterface::class);
    }

    public function testNormalize()
    {
        $contactDto = new ContactDto();
        $contactDto->setSalutation(new StringValue('FEMALE'));
        $contactDto->setFirstname('Jane');
        $contactDto->setLastname('Doe');
        $contactDto->setBirthdate(new DateTime('1989-11-09'));
        $contactDto->setEmail('jane.doe@example.com');
        $contactDto->setMarketingInformation(new BooleanValue(true));

        $array = $this->serializer->normalize($contactDto);

        $this->assertEquals(
            $this->contactDto,
            $array
        );
    }

    public function testSerializer()
    {
        $contactDto = new ContactDto();
        $contactDto->setSalutation(new StringValue('FEMALE'));
        $contactDto->setFirstname('Jane');
        $contactDto->setLastname('Doe');
        $contactDto->setBirthdate(new DateTime('1989-11-09'));
        $contactDto->setEmail('jane.doe@example.com');
        $contactDto->setMarketingInformation(new BooleanValue(true));

        $jsonContent = $this->serializer->serialize($contactDto, 'json');

        $this->assertJsonStringEqualsJsonString(
            json_encode($this->contactDto),
            $jsonContent
        );
    }

    public function testNormalizeSkipNullValue()
    {
        $contactDto = new ContactDto();
        $contactDto->setSalutation(new StringValue('FEMALE'));
        $contactDto->setFirstname('Jane');
        $contactDto->setEmail('jane.doe@example.com');
        $contactDto->setMarketingInformation(new BooleanValue(true));

        $this->assertEquals(
            (function () {
                $array = $this->contactDto;
                unset($array['2']);
                unset($array['4']);
                return $array;
            })(),
            $this->serializer->normalize($contactDto)
        );
    }

    public function testSerializeSkipNullValue()
    {
        $contactDto = new ContactDto();
        $contactDto->setSalutation(new StringValue('FEMALE'));
        $contactDto->setFirstname('Jane');
        $contactDto->setEmail('jane.doe@example.com');
        $contactDto->setMarketingInformation(new BooleanValue(true));

        $this->assertJsonStringEqualsJsonString(
            json_encode((function () {
                $array = $this->contactDto;
                unset($array['2']);
                unset($array['4']);
                return $array;
            })()),
            $this->serializer->serialize($contactDto, 'json')
        );
    }

    public function testDenormalize()
    {
        /**
         * @var $contactDto ContactDto
         */
        $contactDto = $this->serializer->denormalize($this->contactDto, ContactDto::class);

        $this->assertSame('FEMALE', $contactDto->getSalutation()->getValue());
        $this->assertSame('Jane', $contactDto->getFirstname());
        $this->assertSame('Doe', $contactDto->getLastname());
        $this->assertSame('1989-11-09', $contactDto->getBirthdate()->format('1989-11-09'));
        $this->assertSame('jane.doe@example.com', $contactDto->getEmail());
        $this->assertTrue($contactDto->isMarketingInformation()->getValue());
    }

    public function testDeserialize()
    {
        /**
         * @var $contactDto ContactDto
         */
        $contactDto = $this->serializer->deserialize(json_encode($this->contactDto), ContactDto::class, 'json');

        $this->assertSame('FEMALE', $contactDto->getSalutation()->getValue());
        $this->assertSame('Jane', $contactDto->getFirstname());
        $this->assertSame('Doe', $contactDto->getLastname());
        $this->assertSame('1989-11-09', $contactDto->getBirthdate()->format('1989-11-09'));
        $this->assertSame('jane.doe@example.com', $contactDto->getEmail());
        $this->assertTrue($contactDto->isMarketingInformation()->getValue());
    }

    public function testDenormalizeOnNull()
    {
        /**
         * @var $contactDto ContactDto
         */
        $contactDto = $this->serializer->denormalize([], ContactDto::class);

        $this->assertNull($contactDto->getSalutation()?->getValue());
        $this->assertNull($contactDto->getFirstname());
        $this->assertNull($contactDto->getLastname());
        $this->assertNull($contactDto->getBirthdate());
        $this->assertNull($contactDto->getEmail());
        $this->assertNull($contactDto->isMarketingInformation()?->getValue());
    }

    public function testDeserializeOnNull()
    {
        /**
         * @var $contactDto ContactDto
         */
        $contactDto = $this->serializer->deserialize('{}', ContactDto::class, 'json');

        $this->assertNull($contactDto->getSalutation()?->getValue());
        $this->assertNull($contactDto->getFirstname());
        $this->assertNull($contactDto->getLastname());
        $this->assertNull($contactDto->getBirthdate());
        $this->assertNull($contactDto->getEmail());
        $this->assertNull($contactDto->isMarketingInformation()?->getValue());
    }
}
