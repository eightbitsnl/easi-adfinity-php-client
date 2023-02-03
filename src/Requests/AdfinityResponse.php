<?php

namespace Eightbitsnl\EasiAdfinityPhpClient\Requests;

use Psr\Http\Message\ResponseInterface;

class AdfinityResponse
{

    public $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function __call($name, $arguments)
    {
        return call_user_func([$this->response, $name], $arguments);
    }

    public function location()
    {
        $this->response->getHeader("Location");
    }

    public function json()
    {
        return json_decode($this->response->getBody()->getContents(), true);
    }

}

?>
