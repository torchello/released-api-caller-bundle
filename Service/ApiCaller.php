<?php


namespace Released\ApiCallerBundle\Service;


use JMS\Serializer\SerializerInterface as JmsSerializerInterface;
use Released\ApiCallerBundle\Exception\ApiCallerException;
use Released\ApiCallerBundle\Service\Util\ApiCallerConfig;
use Released\ApiCallerBundle\Service\Util\ApiCallerListenerInterface;
use Released\ApiCallerBundle\Transport\TransportInterface;
use Released\ApiCallerBundle\Transport\TransportResponse;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiCaller implements ApiCallerInterface
{

    /** @var ApiCallerConfig[] */
    private $apis;
    /** @var string */
    private $domain;
    /** @var TransportInterface */
    private $transport;
    /** @var JmsSerializerInterface */
    private $serializer;

    function __construct(TransportInterface $transport, JmsSerializerInterface $serializer, $domain, $apis)
    {
        $this->transport = $transport;
        $this->serializer = $serializer;

        $this->domain = rtrim($domain, "/");

        foreach ($apis as $key => $api) {
            $this->apis[$key] = new ApiCallerConfig(
                $api['name'],
                $api['path'],
                isset($api['params']) ? $api['params'] : [],
                isset($api['method']) ? $api['method'] : 'GET',
                isset($api['headers']) ? $api['headers'] : null,
                // TODO: space for request class
                isset($api['response_class']) ? $api['response_class'] : null,
                isset($api['response_format']) ? $api['response_format'] : null
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function makeRequest($api, $values = [], ApiCallerListenerInterface $listener = null, $headers = [])
    {
        $config = $this->checkApi($api, $values);

        $values = $this->cleanValues($values);

        $path = $config->buildPath($values);
        $data = $config->filterParams($values);
        $files = $config->filterFiles($values);
        $headers = $config->mergeHeaders($headers);

        $url = $this->domain . $path;
        $method = $config->getMethod();
        try {
            $result = $this->transport->request($url, $method, $data, $headers, null, $files);
        } catch (\PHPUnit_Framework_Exception $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $result = new TransportResponse("Exception: " . $exception->getMessage(), $exception->getCode()); ;
        }

        if (!is_null($listener)) {
            $listener->onRequest($url, $data, $result->getContent(), $result->getStatus(), $method);
        }

        if (!$result->isOk()) {
            throw new ApiCallerException("Response status is " . $result->getStatus());
        }

        if (!is_null($config->getResponseClass())) {
            $result = new TransportResponse($this->serializer->deserialize(
                $result->getArrayContentAsString(),
                $config->getResponseClass(),
                $config->getResponseFormat() ?: 'json')
            );
        }

        return $result;
    }

    /**
     * @param string $api
     * @param array $values
     * @return ApiCallerConfig
     * @throws ApiCallerException
     */
    private function checkApi($api, $values)
    {
        if (!isset($this->apis[$api])) {
            throw new ApiCallerException("Api '{$api}' does not exists");
        }

        $config = $this->apis[$api];

        $notExistingParams = [];
        foreach ($config->getParams() as $key => $param) {
            if (!isset($values[$key]) && !isset($param['value'])) {
                $notExistingParams[] = $key;
            }

            if (isset($param['class']) && !is_a($values[$key], $param['class'])) {
                throw new ApiCallerException(sprintf("Param '%s' should be instance of '%s'.", $key, $param['class']));
            }
        }

        foreach ($config->getPathParams() as $key => $param) {
            if (!isset($values[$key])) {
                $notExistingParams[] = $key;
            }
        }

        if (!empty($notExistingParams)) {
            throw new ApiCallerException("Not enough parameters: " . implode(", ", $notExistingParams));
        }

        return $config;
    }

    /**
     * Serialize objects
     *
     * @param array $values
     * @return array
     */
    private function cleanValues($values)
    {
        foreach ($values as $key => $value) {
            if (is_object($value)) {
                $normalizer = new GetSetMethodNormalizer();

                $serializer = new Serializer(array($normalizer));
                $normalizer->setSerializer($serializer);

                $values[$key] = $normalizer->normalize($value);
            }
        }

        return $values;
    }

}