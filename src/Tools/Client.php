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
    const DEFAULT_GATEWAY = 'http://open.czb.com/';

    /** @var string 测试网关 */
    const TEST_GATEWAY = 'http://open.czb.com/';

    /** @var string API_ID */
    private $apiId;

    /** @var string API_KEY */
    private $apiKey;

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
     * @param string $api_id API_ID
     * @param string $app_key APP_KEY
     * @param Closure|null $clientFactory
     * @param LoggerInterface|null $logger
     */
    public function __construct(string $api_id, string $app_key, Closure $clientFactory = null, LoggerInterface $logger = null)
    {
        $this->apiId         = $api_id;
        $this->apiKey        = $app_key;
        $this->clientFactory = $clientFactory;
        $this->logger        = $logger ?? new NullLogger();
    }

    /**
     * 发送请求
     * @param string $apiURI 请求地址
     * @param array $parameters 应用级参数
     * @param array $optionPara 可选参数
     * @param bool $needApiId APIID是否参与签名
     * @return ApiResponse
     * @throws ServerException
     */
    protected function request(string $apiURI, array $parameters = [], array $optionPara = [], $needApiId = true): ApiResponse
    {
        $this->logger->debug(sprintf("CzbApi Request [%s] %s", 'GET', $apiURI));
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
            $parameters['Sign'] = $this->getSign($parameters, $needApiId);
            $options['verify']  = false;//关闭SSL验证
            $needApiId && $parameters['APIID'] = $this->apiId;
            if ($optionPara) {
                $parameters = array_merge($parameters, $optionPara);//可选参数不参与签名
            }
            $options["query"] = $parameters;//查询字符串
            $response         = $client->request('GET', $apiURI, $options);
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
     * @param $needApiId
     * @return string
     */
    protected function getSign(array $parameters, $needApiId): string
    {
        if (array_key_exists("Sign", $parameters)) {
            unset($parameters["Sign"]);
        }
        return Sign::getSign($parameters, $needApiId ? $this->apiId : null, $needApiId ? $this->apiKey : null);
    }

    /**
     * 验证签名
     *
     * @param array $parameters POST数组
     * @return bool
     */
    public function verifySign(array $parameters)
    {
        if (!array_key_exists('Sign', $parameters) || empty($parameters['Sign'])) {
            return false;
        }
        $oriSign = $parameters['Sign'];
        $newSign = strtoupper(md5("APIID={$this->apiId}&Account={$parameters['Account']}&OrderID={$parameters['OrderID']}&OutID={$parameters['OutID']}&State={$parameters['State']}&TradeType={$parameters['TradeType']}&TotalPrice={$parameters['TotalPrice']}&APIKEY={$this->apiKey}"));
        if ($oriSign === $newSign) {
            return true;
        }
        return false;
    }

}