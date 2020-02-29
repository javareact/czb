<?php

namespace JavaReact\CzbApi;

use JavaReact\CzbApi\Tools\ApiResponse;
use JavaReact\CzbApi\Tools\Client;

/**
 * Class User 用户接口
 * @package JavaReact\CzbApi
 */
class User extends Client
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

}