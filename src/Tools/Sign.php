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
     * @param array $parameters
     * @param $apiId
     * @param $apiKey
     * @return string
     */
    public static function getSign(array $parameters, $apiId, $apiKey): string
    {
        ksort($parameters, SORT_STRING);//不分大小写排序
        //中文URL_ENCODE
        $str = http_build_query($parameters);
        if (!is_null($apiId)) {
            $str = "APIID=$apiId&" . $str;
        }
        if (!is_null($apiKey)) {
            $str = rtrim($str, '&') . "&APIKEY=$apiKey";
        }
        //转大写
        return strtoupper(md5($str));
    }
}