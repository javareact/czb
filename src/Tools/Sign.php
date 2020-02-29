<?php
declare(strict_types=1);

namespace JavaReact\CzbApi\Tools;
/**
 * 车主邦签名算法
 * Class Sign
 * @package JavaReact\CzbApi
 */
class Sign
{
    /**
     * 计算签名
     *
     * @param array $parameters
     * @param string $apiSecret
     * @return string
     */
    public static function getSign(array $parameters, string $apiSecret): string
    {
        ksort($parameters, SORT_STRING);//不分大小写排序
        $str = '';
        foreach ($parameters as $key => $val) {
            $str .= $key . $val;
        }
        $str = $apiSecret . $str . $apiSecret;
        return strtolower(md5($str));
    }
}