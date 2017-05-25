<?php

namespace Released\ApiCallerBundle\Tests;

use Released\ApiCallerBundle\Service\ApiCallerInterface;
use Released\ApiCallerBundle\Service\Util\ApiCallerListenerInterface;

class ApiCallerStub implements ApiCallerInterface
{

    /**
     * {@inheritdoc}
     */
    public function makeRequest($api, $values = [], ApiCallerListenerInterface $listener = null, $headers = null)
    {

    }
}
