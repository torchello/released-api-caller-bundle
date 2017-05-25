<?php


namespace Released\ApiCallerBundle\Service;


use Released\ApiCallerBundle\Exception\ApiCallerException;
use Released\ApiCallerBundle\Service\Util\ApiCallerListenerInterface;
use Released\ApiCallerBundle\Transport\TransportResponse;

interface ApiCallerInterface
{

    /**
     * @param string $api Name of API to call
     * @param array $values
     * @param ApiCallerListenerInterface $listener
     * @param array $headers
     * @return TransportResponse
     * @throws ApiCallerException
     */
    public function makeRequest($api, $values = [], ApiCallerListenerInterface $listener = null, $headers = null);

}