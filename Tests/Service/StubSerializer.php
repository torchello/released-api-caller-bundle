<?php

namespace Released\ApiCallerBundle\Tests\Service;


use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class StubSerializer implements SerializerInterface
{

    public function serialize($data, $format, SerializationContext $context = null)
    {
    }

    public function deserialize($data, $type, $format, DeserializationContext $context = null)
    {
    }
}

























































































