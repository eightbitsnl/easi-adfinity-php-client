<?php

namespace Eightbitsnl\EasiAdfinityPhpClient\Requests;

use BadMethodCallException;
use Eightbitsnl\EasiAdfinityPhpClient\AdfinityApiClient;

class BaseRequest
{
    public const HTTP_METHOD = null;
    public const URI = null;

    /**
     * HTTP Methods
     */
    public const HTTP_GET = "GET";
    public const HTTP_POST = "POST";
    public const HTTP_DELETE = "DELETE";
    public const HTTP_PATCH = "PATCH";

    protected $querystring = [];
    protected $uri_path = '';

    /**
     * @var AdfinityApiClient
     */
    protected AdfinityApiClient $client;

    /**
     *
     * @param AdfinityApiClient $client
     */
    public function __construct(AdfinityApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return AdfinityResponse
     */
    public function __invoke() : AdfinityResponse
    {
        return $this->send();
    }

    public function setArguments($arguments)
    {
        $this->uri_path = implode('/', $arguments);

        return $this;
    }

    public function __call($name, $arguments)
    {
        if( in_array($name, ['json', 'location']) )
        {
            return $this->send()->$name(...$arguments);
        }

        throw new BadMethodCallException();
    }

    public function getFullUrl()
    {
        return $this->client->base_url . static::URI . $this->parseUriPath(). $this->parseQueryString();
    }

    public function send()
    {
        return new AdfinityResponse(
            $this->client->performHttpCallToFullUrl(
                static::HTTP_METHOD,
                $this->getFullUrl()
            )
        );
    }

    public function parseUriPath()
    {
        if( empty($this->uri_path) )
            return '';

        return '/'. trim(ltrim($this->uri_path, '/'));
    }

    public function parseQueryString()
    {
        if(! count($this->querystring) )
            return '';

        return '?'. implode('&', $this->querystring);
    }

}

?>
