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
        return call_user_func_array([$this->response, $name], $arguments);
    }

    public function adfinityLocation()
    {
        $location = $this->response->getHeader("Location");

        if (is_array($location) && count($location)) {
            return $location[0];
        }

        return null;
    }

    public function json()
    {
        return json_decode($this->response->getBody()->getContents(), true);
    }

}

?>
