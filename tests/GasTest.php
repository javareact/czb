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
        $res = $this->client->login('', '');
        var_export($res->result());
    }

    /**
     * 初始化油站数据
     */
    public function testQueryGasInfoListOilNoNew()
    {
        $res = $this->client->queryGasInfoListOilNoNew('');
        var_export($res->result());
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
        $res = $this->client->platformOrderInfoV2('', '', '');
        var_export($res->result());
    }

}