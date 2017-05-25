<?php

namespace Released\ApiCallerBundle\Transport;


class TransportResponse
{

    protected $status;
    /** @var mixed */
    protected $content;

    function __construct($content, $status = 200)
    {
        $this->content = $content;
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isOk()
    {
        return $this->status >= 200 && $this->status < 300;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getArrayContentAsString()
    {
        return is_array($this->content) ? json_encode($this->content) : $this->content;
    }

}