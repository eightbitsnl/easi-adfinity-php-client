<?php

namespace Eightbitsnl\EasiAdfinityPhpClient\Requests;

use BadMethodCallException;
use Eightbitsnl\EasiAdfinityPhpClient\AdfinityApiClient;
use Eightbitsnl\EasiAdfinityPhpClient\Exceptions\NotFoundException;
use GuzzleHttp\Exception\ClientException;

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
    public const HTTP_PUT = "PUT";

    protected $querystring = [];
    protected $uri_path = '';

    /**
     * @var AdfinityApiClient
     */
    protected AdfinityApiClient $client;

    /**
     * Request Headers
     *
     * @var array
     */
    protected array $request_headers = [];

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

    public function setHeader($key, $value)
    {
        $this->request_headers[$key] = $value;
        return $this;
    }

    public function getFullUrl()
    {
        return $this->client->base_url . static::URI . $this->parseUriPath(). $this->parseQueryString();
    }

    public function send($httpBody = null)
    {

        try
        {
            return new AdfinityResponse(
                $this->client->performHttpCallToFullUrl(
                    static::HTTP_METHOD,
                    $this->getFullUrl(),
                    $this->request_headers,
                    $httpBody
                )
            );
        }
        catch(ClientException $guzzleException)
        {
            if($guzzleException->getResponse()->getStatusCode() == 404)
            {
                throw (new NotFoundException)->setClientException($guzzleException);
            }

            throw $guzzleException;
        }
    }

    public function parseUriPath()
    {
        if( empty($this->uri_path) )
            return '';

        return '/'. trim(ltrim($this->uri_path, '/'));
    }

    public function addQueryStringParam($key, $value)
    {
        $this->querystring[$key] = $value;
        return $this;
    }

    public function parseQueryString()
    {
        if(! count($this->querystring) )
            return '';

        return '?'. http_build_query($this->querystring);
    }

}

?>
