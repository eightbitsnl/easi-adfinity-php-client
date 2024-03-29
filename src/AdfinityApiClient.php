<?php

namespace Eightbitsnl\EasiAdfinityPhpClient;

use BadMethodCallException;
use Eightbitsnl\EasiAdfinityPhpClient\Requests\V1\GetAccountingEntries;
use Eightbitsnl\EasiAdfinityPhpClient\Requests\V2\GetAccountingEntries as V2GetAccountingEntries;
use Eightbitsnl\EasiAdfinityPhpClient\Requests\V1\GetGeneralAccounts;
use Eightbitsnl\EasiAdfinityPhpClient\Requests\V1\PostAccountingEntries;
use Eightbitsnl\EasiAdfinityPhpClient\Requests\V1\PostFileUploadRequest;
use Eightbitsnl\EasiAdfinityPhpClient\Requests\V1\PutAccountingEntries;
use Eightbitsnl\EasiAdfinityPhpClient\Requests\V2\GetCompanies;
use Eightbitsnl\EasiAdfinityPhpClient\Requests\V2\PostCompanies;
use Eightbitsnl\EasiAdfinityPhpClient\Requests\V2\PostDocumentManagement;
use Eightbitsnl\EasiAdfinityPhpClient\Requests\V2\PutCompanies;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Eightbitsnl\EasiAdfinityPhpClient\AdfinityApiClient
 *
 * @property Requests\V1\GetAccountingEntries $V1GetAccountingEntries
 * @property Requests\V1\GetGeneralAccounts $V1GetGeneralAccounts
 * @property Requests\V2\GetCompanies $V2GetCompanies
 * @method Requests\AdfinityResponse V1GetAccountingEntries()
 * @method Requests\AdfinityResponse V1GetGeneralAccounts()
 * @method Requests\AdfinityResponse V1PostAccountingEntries()
 * @method Requests\AdfinityResponse V1PutAccountingEntries()
 * @method Requests\AdfinityResponse V1PostFileUploadRequest()
 * @method Requests\AdfinityResponse V2GetCompanies()
 * @method Requests\AdfinityResponse V2PostCompanies()
 * @method Requests\AdfinityResponse V2PostDocumentManagement()
 * @method Requests\AdfinityResponse V2PutCompanies()
 */
class AdfinityApiClient
{

    /**
     * Map Requests
     *
     * @return void
     */
    private function mapRequests()
    {
        $this->requests = [
            'V1GetAccountingEntries' => GetAccountingEntries::class,
            'V2GetAccountingEntries' => V2GetAccountingEntries::class,
            'V1GetGeneralAccounts' => GetGeneralAccounts::class,
            'V1PostAccountingEntries' => PostAccountingEntries::class,
            'V1PutAccountingEntries' => PutAccountingEntries::class,
            'V1PostFileUploadRequest' => PostFileUploadRequest::class,
            'V2GetCompanies' => GetCompanies::class,
            'V2PostCompanies' => PostCompanies::class,
            'V2PostDocumentManagement' => PostDocumentManagement::class,
            'V2PutCompanies' => PutCompanies::class
        ];
    }

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
     * Timeout Delay
     *
     * @var int|null
     */
    protected ?int $timeout_delay = 120;

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
     * set timeout delay
     *
     * @param string $timeout_delay
     * @return self
     */
    public function setTimeoutDelay(string $timeout_delay) : self
    {
        $this->timeout_delay = $timeout_delay;
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

        $this->mapRequests();
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
            return (new $this->requests[$name]($this))->setArguments($arguments);
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
     * Get Request Headers
     *
     * @return array Request Headers
     */
    private function mergeRequestHeaders($headers = []) : array
    {
        return array_merge(
            [
                "Authorization" => "Basic {$this->getAuthToken()}",
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "adfinity-database" => $this->database,
                "adfinity-envir" => $this->envir,
                "adfinity-exercice" => $this->exercice,
                "adfinity-period" => $this->period,
                "adfinity-language" => $this->language,
                "adfinity-Dispatch-Timeout-Delay" => $this->timeout_delay,
            ],
            $headers
        );
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
    public function performHttpCallToFullUrl($httpMethod, $url, $headers = [], $httpBody = null): ResponseInterface
    {

        $options = [
            "verify" => $this->verify_ssl,
            "headers" => $this->mergeRequestHeaders($headers)
        ];

        if( !is_null($httpBody) )
        {
            if( $options['headers']['Content-Type'] == "application/json")
                $options['json'] = $httpBody;
            else
                $options['body'] = $httpBody;
        }

        return $this->httpClient->request($httpMethod, $url, $options);
    }
}

?>
