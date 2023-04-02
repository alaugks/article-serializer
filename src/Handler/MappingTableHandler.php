<?php declare(strict_types=1);

namespace App\Handler;

use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class MappingTableHandler implements SubscribingHandlerInterface
{
    public const HANDLER_TYPE = 'MappingTable';

    public static function getSubscribingMethods(): array
    {
        $methods = [];

        $methods[] = [
            'type' => self::HANDLER_TYPE,
            'format' => 'json',
            'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
            'method' => 'serialize',
        ];

        $methods[] = [
            'type' => self::HANDLER_TYPE,
            'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
            'format' => 'json',
            'method' => 'deserialize',
        ];

        return $methods;
    }

    public function serialize(
        SerializationVisitorInterface $visitor,
        string|bool|null $value,
        array $type,
        SerializationContext $context
    ): ?string
    {
        $mappingTable = $this->getMappingTable($type);

        if (null === $value || '' === $value) {
            return ''; // Reset value in CRM
        }

        foreach ($mappingTable as $mKey => $mValue) {
            if ($value === $mValue) {
                return (string)$mKey; // Force string
            }
        }
        return null;
    }

    public function deserialize(
        DeserializationVisitorInterface $visitor,
        $value,
        array $type
    ): mixed
    {
        $mappingTable = $this->getMappingTable($type);

        foreach ($mappingTable as $mKey => $mValue) {
            if ((string)$value === (string)$mKey) {
                return $mValue;
            }
        }
        return null;
    }

    private function getMappingTable(array $type): array
    {
        $mappingTable = [];

        if (!isset($type['params'][0])) {
            throw new \InvalidArgumentException('mapping_table param not defined');
        }

        // Table as JSON
        if ($array = json_decode($type['params'][0], true)) {
            $mappingTable = $array;
        }

        // Table from Constant as array
        else if (defined($type['params'][0])) {
            $mappingTable = constant($type['params'][0]);
        }

        if (!is_array($mappingTable) || !count($mappingTable)) {
            throw new \InvalidArgumentException('mapping_table is not an array or empty');
        }

        return $mappingTable;
    }
}
