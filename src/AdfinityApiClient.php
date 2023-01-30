<?php

namespace Eightbitsnl\EasiAdfinityPhpClient;

use BadMethodCallException;
use Eightbitsnl\EasiAdfinityPhpClient\Requests\V1\GetGeneralAccounts;
use Eightbitsnl\EasiAdfinityPhpClient\Requests\V2\GetCompanies;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Eightbitsnl\EasiAdfinityPhpClient\AdfinityApiClient
 *
 * @property Requests\V1\GetGeneralAccounts $V1GetGeneralAccounts
 * @property Requests\V2\GetCompanies $V2GetCompanies
 * @method Requests\AdfinityResponse V1GetGeneralAccounts()
 * @method Requests\AdfinityResponse V2GetCompanies()
 */
class AdfinityApiClient
{
    private $requests = [];

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * Base URL
     *
     * @var string
     */
    public string $base_url = "https://tools.adfinity.be/EASIMaster/adfinity/api/rest";

    /**
     * Verify SSL
     *
     * @var bool
     */
    protected bool $verify_ssl = true;

    /**
     * Username
     *
     * @var string
     */
    protected string $username = "";

    /**
     * Username
     *
     * @var string
     */
    protected string $password = "";

    /**
     * Database
     *
     * @var string
     */
    protected string $database = "";

    /**
     * envir
     *
     * @var string
     */
    protected string $envir = "";

    /**
     * exercice
     *
     * @var string|null
     */
    protected ?string $exercice = null;

    /**
     * period
     *
     * @var string|null
     */
    protected ?string $period = null;

    /**
     * language
     *
     * @var string|null
     */
    protected ?string $language = null;

    /**
     * set base_url
     *
     * @param string $base_url
     * @return self
     */
    public function setBaseUrl(string $base_url) : self
    {
        $this->base_url = rtrim(trim($base_url), '/');
        return $this;
    }

    /**
     * set verify_ssl
     *
     * @param string $verify_ssl
     * @return self
     */
    public function setVerifySsl(bool $verify_ssl = true) : self
    {
        $this->verify_ssl = $verify_ssl;
        return $this;
    }

    /**
     * set username
     *
     * @param string $username
     * @return self
     */
    public function setUsername(string $username) : self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * set password
     *
     * @param string $password
     * @return self
     */
    public function setPassword(string $password) : self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * set database
     *
     * @param string $database
     * @return self
     */
    public function setDatabase(string $database) : self
    {
        $this->database = $database;
        return $this;
    }

    /**
     * set envir
     *
     * @param string $envir
     * @return self
     */
    public function setEnvir(string $envir) : self
    {
        $this->envir = $envir;
        return $this;
    }

    /**
     * Constructor
     *
     * @param ClientInterface|null $httpClient
     */
    public function __construct(ClientInterface $httpClient = null)
	{
		$this->httpClient = $httpClient ?? new Client();

        $this->initializeEndpoints();
    }

    /**
     * Initialize Endpoints
     *
     * @return void
     */
    private function initializeEndpoints()
    {
        $this->requests = [
            'V1GetGeneralAccounts' => new GetGeneralAccounts($this),
            'V2GetCompanies' => new GetCompanies($this)
        ];
    }

    /**
     * Magic Method
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if( $this->isRequest($name) )
        {
            return $this->requests[$name]->setArguments($arguments);
        }

        throw new BadMethodCallException();
    }

    public function __get($name)
    {
        if( $this->isRequest($name) )
        {
            return $this->requests[$name]();
        }

        trigger_error("Undefined Property");
    }

    protected function isRequest($name)
    {
        return array_key_exists($name, $this->requests);
    }

    /**
     * Get Auth Token
     *
     * @return string auth token
     */
    private function getAuthToken() : string
    {
        return base64_encode("{$this->username}:{$this->password}");
    }

    /**
     * Perform a http call
     *
     * @param string $httpMethod
     * @param string $url
     * @param string|null $httpBody
     * @throws GuzzleException
     * @return ResponseInterface
     */
    public function performHttpCallToFullUrl($httpMethod, $url, $httpBody = null): ResponseInterface
    {
        $options = [
            'verify' => $this->verify_ssl,
            "headers" => [
                'Authorization' => "Basic {$this->getAuthToken()}",
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "adfinity-database" => $this->database,
                "adfinity-envir" => $this->envir,
                "adfinity-exercice" => $this->exercice,
                "adfinity-period" => $this->period,
                "adfinity-language" => $this->language,
            ]
        ];

        return $this->httpClient->request($httpMethod, $url, $options);
    }
}

?>
