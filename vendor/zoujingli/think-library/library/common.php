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

if (!function_exists('encode')) {
    /**
     * UTF8 字符串加密
     * @param string $string
     * @return string
     */
    function encode($string)
    {
        return \library\tools\Crypt::encode($string);
    }
}

if (!function_exists('decode')) {
    /**
     * UTF8 字符串解密
     * @param string $string
     * @return string
     */
    function decode($string)
    {
        return \library\tools\Crypt::decode($string);
    }
}

if (!function_exists('emoji_encode')) {
    /**
     * Emoji 表情编码
     * @param string $string
     * @return string
     */
    function emoji_encode($string)
    {
        return \library\tools\Crypt::emojiEncode($string);
    }
}

if (!function_exists('emoji_decode')) {
    /**
     * Emoji 表情解析
     * @param string $string
     * @return string
     */
    function emoji_decode($string)
    {
        return \library\tools\Crypt::emojiDecode($string);
    }
}