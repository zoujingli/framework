<?php

// +----------------------------------------------------------------------
// | Library for ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://library.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/ThinkLibrary
// +----------------------------------------------------------------------

namespace library\tools;

/**
 * 数据加密解密工具
 * Class Crypt
 * @package library\tools
 */
class Crypt
{
    /**
     * UTF8字符串加密
     * @param string $string
     * @return string
     */
    public static function encode($string)
    {
        $string = iconv('utf-8', 'gbk', $string);
        list($chars, $length) = ['', strlen($string)];
        for ($i = 0; $i < $length; $i++) {
            $chars .= str_pad(base_convert(ord($string[$i]), 10, 36), 2, 0, 0);
        }
        return $chars;
    }

    /**
     * UTF8字符串解密
     * @param string $string
     * @return string
     */
    public static function decode($string)
    {
        $chars = '';
        foreach (str_split($string, 2) as $char) {
            $chars .= chr(intval(base_convert($char, 36, 10)));
        }
        return iconv('gbk', 'utf-8', $chars);
    }

}