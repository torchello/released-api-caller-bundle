<?php


namespace Released\ApiCallerBundle\Service\Util;


interface ApiCallerListenerInterface
{

    /**
     * @param string $url
     * @param array|string $request
     * @param array|string $response
     * @param int $status
     * @param string $method GET|POST|...
     */
    public function onRequest($url, $request, $response, $status, $method);

}