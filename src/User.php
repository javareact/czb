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
     * @param string $Account 充值号码
     * @return ApiResponse
     */
    public function login($Account)
    {
        $params = [
            'Account' => $Account,
        ];
        return $this->request("Api/PayMobile.aspx", $params);
    }

}