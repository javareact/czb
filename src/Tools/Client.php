<?php
declare(strict_types=1);

namespace JavaReact\CzbApi\Tools;

use Closure;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use JavaReact\CzbApi\Exception\ClientException;
use JavaReact\CzbApi\Exception\ServerException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class Client
 * @package JavaReact\CzbApi
 */
abstract class Client
{
    /** @var string 默认网关 */
    const DEFAULT_GATEWAY = 'https://test-mcs.czb365.com/services/v3/';

    /** @var string 测试网关 */
    const TEST_GATEWAY = 'https://test-mcs.czb365.com/services/v3/';

    /** @var string apiKey */
    private $apiKey;

    /** @var string apiSecret */
    private $apiSecret;

    /**
     * @var Closure
     */
    private $clientFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Client constructor.
     * @param string $apiKey
     * @param string $apiSecret
     * @param Closure|null $clientFactory
     * @param LoggerInterface|null $logger
     */
    public function __construct(string $apiKey, string $apiSecret, Closure $clientFactory = null, LoggerInterface $logger = null)
    {
        $this->apiKey        = $apiKey;
        $this->apiSecret     = $apiSecret;
        $this->clientFactory = $clientFactory;
        $this->logger        = $logger ?? new NullLogger();
    }

    /**
     * 发送请求
     * @param string $apiURI 请求地址
     * @param array $parameters 应用级参数
     * @return ApiResponse
     */
    protected function request(string $apiURI, array $parameters = []): ApiResponse
    {
        $this->logger->debug(sprintf("CzbApi Request [%s] %s", 'POST', $apiURI));
        try {
            $clientFactory = $this->clientFactory;
            if ($clientFactory instanceof Closure) {
                /** @var ClientInterface $client */
                $client = $clientFactory();
            } else {
                $client = new \GuzzleHttp\Client;
            }
            if (!$client instanceof ClientInterface) {
                throw new ClientException(sprintf('The client factory should create a %s instance.', ClientInterface::class));
            }
            if (empty($client->getConfig('base_uri'))) {
                $apiURI = self::DEFAULT_GATEWAY . $apiURI;//缺省网关
            }
            $parameters['app_key']   = $this->apiKey;
            $parameters['timestamp'] = bcmul(strval(time()), '1000', 0);
            $parameters['sign']      = $this->getSign($parameters);
            $options['verify']       = false;//关闭SSL验证
            $options["form_params"]  = $parameters;//application/x-www-form-urlencoded POST请求
            $response                = $client->request('POST', $apiURI, $options);
        } catch (TransferException $e) {
            $message = sprintf("Something went wrong when calling fulu (%s).", $e->getMessage());
            $this->logger->error($message);
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        } catch (GuzzleException $e) {
            $message = sprintf("Something went wrong when calling fulu (%s).", $e->getMessage());
            $this->logger->error($message);
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
        return new ApiResponse($response);
    }

    /**
     * 获取签名
     *
     * @param array $parameters
     * @return string
     */
    protected function getSign(array $parameters): string
    {
        if (array_key_exists("sign", $parameters)) {
            unset($parameters["sign"]);
        }
        if (array_key_exists("token", $parameters)) {
            unset($parameters["token"]);
        }
        if (array_key_exists("app_version", $parameters)) {
            unset($parameters["app_version"]);
        }
        return Sign::getSign($parameters, $this->apiSecret);
    }

}