<?php

namespace Test\CzbApi;

use JavaReact\CzbApi\Tools\ApiResponse;
use PHPUnit\Framework\TestCase;

/**
 * Class BaseTest 测试基类
 * @package Test\CzbApi
 */
class BaseTest extends TestCase
{

    /**
     * @var bool 调试模式
     */
    protected $isDebug = false;

    /**
     * 输出结果
     * @param ApiResponse $response
     */
    public function dump(ApiResponse $response)
    {
        $this->isDebug && var_export($response->json());
    }

}