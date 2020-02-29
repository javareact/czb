<?php

namespace Test\CzbApi;

use JavaReact\CzbApi\Gas;

class GasTest extends BaseTest
{

    private $client;

    protected function setUp(): void
    {
        $this->client = new Gas('', '');
    }

    /**
     * 平台授权登录
     */
    public function testLogin()
    {
        $this->client->login('', '');
    }

    /**
     * 初始化油站数据
     */
    public function testQueryGasInfoListOilNoNew()
    {
        $this->client->queryGasInfoListOilNoNew('');
    }

    /**
     * 根据用户查询油站状态和油价
     */
    public function testQueryPriceByPhone()
    {
        $this->client->queryPriceByPhone('', '', '');
    }

    /**
     * 查询订单
     */
    public function testPlatformOrderInfoV2()
    {
        $this->client->platformOrderInfoV2('', '', '');
    }

}