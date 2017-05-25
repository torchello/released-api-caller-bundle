<?php


namespace Released\ApiCallerBundle\Service\Factory;


use JMS\Serializer\SerializerInterface;
use Released\ApiCallerBundle\Exception\ApiCallerException;
use Released\ApiCallerBundle\Transport\TransportInterface;
use Released\ApiCallerBundle\Service\ApiCaller;
use Released\ApiCallerBundle\Service\ApiCallerInterface;

class ApiCallerFactory
{

    /** @var TransportInterface */
    protected $transport;

    /** @var array */
    protected $cases;

    /** @var ApiCallerInterface[] */
    protected $instances = [];

    /** @var SerializerInterface */
    protected $serializer;

    public function __construct(TransportInterface $transport, SerializerInterface $serializer, array $cases)
    {
        $this->transport = $transport;
        $this->cases = $cases;

        $this->serializer = $serializer;
    }

    /**
     * @param string $case
     * @return ApiCallerInterface
     * @throws ApiCallerException
     */
    public function createApiCaller($case)
    {
        $this->checkCase($case);

        if (!isset($this->instances[$case])) {
            $caseConfig = $this->cases[$case];

            $instance = new ApiCaller($this->transport, $this->serializer, $caseConfig['domain'], $caseConfig['endpoints']);
            $this->instances[$case] = $instance;
        }

        return $this->instances[$case];
    }

    /**
     * @param $case
     * @throws ApiCallerException
     */
    protected function checkCase($case)
    {
        if (!isset($this->cases[$case])) {
            throw new ApiCallerException("ApiCaller case '{$case}' is not defined.");
        }
    }

}