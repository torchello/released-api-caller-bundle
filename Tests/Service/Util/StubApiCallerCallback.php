<?php


namespace Released\ApiCallerBundle\Tests\Unit\Service\Util;


use Released\ApiCallerBundle\Service\Util\ApiCallerListenerInterface;

class StubApiCallerCallback implements ApiCallerListenerInterface
{
    /**
     * {@inheritdoc}
     */
    public function onRequest($url, $request, $response, $status, $method)
    {

    }
}