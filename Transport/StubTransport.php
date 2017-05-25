<?php

namespace Released\ApiCallerBundle\Transport;


class StubTransport implements TransportInterface {

    /**
     * @inheritdoc
     */
    public function request($url, $method = self::METHOD_GET, $data = null, $headers = null, $cookies = null, $files = null)
    {

    }

}