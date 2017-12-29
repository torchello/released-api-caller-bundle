<?php


namespace Released\ApiCallerBundle\Service\Util;


use Symfony\Component\Console\Output\OutputInterface;

class ApiCallerListenerOutputConsole implements ApiCallerListenerInterface
{
    /** @var OutputInterface */
    protected $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /** {@inheritdoc} */
    public function onRequest($url, $request, $response, $status, $method)
    {
        $this->output->writeln(print_r([
            'url' => $url,
            'request' => $request,
            'response' => $response,
            'status' => $status,
            'method' => $method
        ], 1));
    }
}