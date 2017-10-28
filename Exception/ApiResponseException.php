<?php


namespace Released\ApiCallerBundle\Exception;


use Released\ApiCallerBundle\Transport\TransportResponse;
use Throwable;

class ApiResponseException extends \RuntimeException
{
    /** @var TransportResponse */
    protected $response;

    public function __construct(TransportResponse $response, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->response = $response;
    }

    /** @return TransportResponse */
    public function getResponse()
    {
        return $this->response;
    }

}