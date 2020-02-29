<?php

namespace JavaReact\CzbApi;

use JavaReact\CzbApi\Tools\ApiResponse;
use JavaReact\CzbApi\Tools\Client;

class Gas extends Client
{
    /**
     * 平台授权登录
     *
     * @param int $platformType 渠道编码，对接时车主邦提供
     * @param string $platformCode 平台用户唯一标识(手机号)
     * @return ApiResponse
     */
    public function login(int $platformType, string $platformCode)
    {
        $params = [
            'platformType' => $platformType,
            'platformCode' => $platformCode,
        ];
        return $this->request("begin/platformLoginSimpleAppV4", $params);
    }

    /**
     * 初始化油站数据
     * @param string $channelId 平台code|渠道编码
     * @return Tools\ApiResponse
     */
    public function queryGasInfoListOilNoNew($channelId)
    {
        $params = [
            'channelId' => $channelId,
        ];
        return $this->request("gas/queryGasInfoListOilNoNew", $params);
    }

    /**
     * 根据用户查询油站状态和油价<p>
     * <font color="red">该接口调用前需调用用户接口中“平台授权登录”接口，不需传token参数</font>
     * @param string $gasIds
     * @param string $platformType
     * @param string $phone
     * @return Tools\ApiResponse
     */
    public function queryPriceByPhone(string $gasIds, string $platformType, string $phone)
    {
        $params = [
            'channelId'    => $gasIds,
            'platformType' => $platformType,
            'phone'        => $phone,
        ];
        return $this->request("gas/queryGasInfoListOilNoNew", $params);
    }

    /**
     * 查询订单
     *
     * @param string $orderSource
     * @param int $pageIndex
     * @param int $pageSize
     * @param array $extraParam 额外参数 beginTime endTime orderStatus orderId phone
     * @return Tools\ApiResponse
     */
    public function platformOrderInfoV2(string $orderSource, $pageIndex = 1, $pageSize = 10, $extraParam = [])
    {
        $params = [
            'orderSource' => $orderSource,
            'pageIndex'   => $pageIndex,
            'pageSize'    => $pageSize,
        ];
        $params = array_merge($params, array_filter($extraParam));
        return $this->request("orderws/platformOrderInfoV2", $params);
    }

}