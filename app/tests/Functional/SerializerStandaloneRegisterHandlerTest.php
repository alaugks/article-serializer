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

        $contactDto = new ContactDto();
        $contactDto->setSalutation('FEMALE');
        $contactDto->setMarketingInformation(false);

        $jsonContent = $serializer->serialize($contactDto, 'json');

        $this->assertJsonStringEqualsJsonString(
            '{
                "46": "2",
                "100674": "2"
            }',
            $jsonContent
        );
    }
}
