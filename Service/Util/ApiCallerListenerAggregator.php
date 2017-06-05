<?php


namespace Released\ApiCallerBundle\Service\Util;


class ApiCallerListenerAggregator implements ApiCallerListenerInterface
{
    /** @var ApiCallerListenerInterface[] */
    protected $listeners = [];

    public function addListener(ApiCallerListenerInterface $listener)
    {
        $this->listeners[] = $listener;
    }

    /** {@inheritdoc} */
    public function onRequest($url, $request, $response, $status, $method)
    {
        foreach ($this->listeners as $listener) {
            $listener->onRequest($url, $request, $response, $status, $method);
        }
    }
}