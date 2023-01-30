# Easi Adfinity Accounting Software API client for PHP

https://tools.adfinity.be/easimaster/openapi/index.html

## Usage

```php
use Eightbitsnl\EasiAdfinityPhpClient\AdfinityApiClient;
use Eightbitsnl\EasiAdfinityPhpClient\Requests\V1\GetGeneralAccounts;


$client = (new AdfinityApiClient())
    ->setBaseUrl("http://example.com:8181/adfinity/api/rest")
    ->setVerifySsl(false)
    ->setUsername("EXAMPLE_USERNAME")
    ->setPassword("EXAMPLE_PASSWORD")
    ->setDatabase("EXAMPLE_DATABASE")
    ->setEnvir("123456");

// Access Response as property
$response = $client->V1GetGeneralAccounts;

// Or as method, so you can chain additional methods, for example:
$response = $client->V1GetGeneralAccounts()
    ->filter('label', '%vermogen%')
    ->send();

// Or
$response = $client->V1GetGeneralAccounts()
    ->filter('label', '%vermogen%')
    ->send();

// Or
$response = $client->V1GetGeneralAccounts()->json();

// Or
$response = $client->V1GetGeneralAccounts()
    ->filter('type', 'ne', 'CHA')
    ->filter('label', '%vermogen%')
    ->json();
```
