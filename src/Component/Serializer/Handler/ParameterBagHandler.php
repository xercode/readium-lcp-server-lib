<?php

namespace Xercode\Readium\Component\Serializer\Handler;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\Context;
use Xercode\Readium\Component\ParameterBag;

final class ParameterBagHandler implements SubscribingHandlerInterface
{

    /**
     * Return format:
     *
     *      array(
     *          array(
     *              'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
     *              'format' => 'json',
     *              'type' => 'DateTime',
     *              'method' => 'serializeDateTimeToJson',
     *          ),
     *      )
     *
     * The direction and method keys can be omitted.
     *
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => ParameterBag::class,
                'method' => 'serializeParameterBagToJson',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => ParameterBag::class,
                'method' => 'deserializeParameterBagToJson',
            ],
        ];
    }

    public function serializeParameterBagToJson(
        JsonSerializationVisitor $visitor,
        ParameterBag $attributes,
        array $type,
        Context $context
    ) {
        if ($attributes !== null && $attributes->count() > 0) {
            return $attributes->all();
        }

        return null;

    }

    public function deserializeParameterBagToJson(
        JsonDeserializationVisitor $visitor,
        $attributesAsArray,
        array $type,
        Context $context
    ) {
        return new ParameterBag($attributesAsArray);
    }

}
