<?php declare(strict_types=1);

namespace App\Service;

use App\Dto\ContactDto;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;

class CrmSerializerService
{
    private Serializer $serializer; // Only JMSSerializer

    public function __construct(SerializerInterface $serializer) {
        $this->serializer = $serializer;
    }

    public function normalize(ContactDto $contactDto): array
    {
        return $this->serializer->toArray($contactDto);
    }

    public function denormalize(array $data): ContactDto
    {
        return $this->serializer->fromArray($data, ContactDto::class);
    }

    public function serialize(ContactDto $contactDto): string
    {
        return $this->serializer->serialize($contactDto, 'json');
    }

    public function deserialize(string $json): ContactDto
    {
        return $this->serializer->deserialize($json, ContactDto::class, 'json');
    }
}
