<?php declare(strict_types=1);

namespace App\Tests\Functional;

use App\Dto\ContactDto;
use App\Handler\MappingTableHandler;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SerializerStandaloneRegisterHandlerTest extends KernelTestCase
{
    public function testSerializerBuilderCreateAndRegisterHandler()
    {
        $serializer = SerializerBuilder::create()
            ->configureHandlers(function(HandlerRegistry $registry) {
                $registry->registerSubscribingHandler(new MappingTableHandler());
            })
            ->build();

        $ContactDto = new ContactDto();
        $ContactDto->setSalutation('FEMALE');
        $ContactDto->setMarketingInformation(false);

        $jsonContent = $serializer->serialize($ContactDto, 'json');

        $this->assertJsonStringEqualsJsonString(
            '{
                "46": "2",
                "100674": "2"
            }',
            $jsonContent
        );
    }
}
