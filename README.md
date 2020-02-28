### 车主邦-openapi
### PHP-SDK

# 1. Require with Composer
```
composer require javareact/czb
```

# 2. Example
```
use GuzzleHttp\Client;
use JavaReact\CzbApi\Balance;

$goods = new Balance("apiKey", "secret", function() {
    return new Client([
        "base_uri" => \JavaReact\CzbApi\Client::DEFAULT_GATEWAY,//可省略
    ]);
});

$response = $balance->balanceGet("productid");
if($response->getStatusCode() == 200) {
    $json = $response->json();
    $result = $response->result();
}
```