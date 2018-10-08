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

use think\facade\Log;
use think\facade\Session;

/**
 * Cors跨域及Token支持类
 * Class Cors
 * @package library\tools
 */
class Cors
{

    /**
     * 获取会话令牌
     * @return string
     */
    public static function getSessionToken()
    {
        if (PHP_SESSION_ACTIVE !== session_status()) {
            Session::init(config('session.'));
        }
        return Crypt::encode(session_name() . '=' . session_id());
    }

    /**
     * 应用会话令牌
     */
    public static function setSessionToken()
    {
        if (PHP_SESSION_ACTIVE !== session_status()) {
            Session::init(config('session.'));
        }
        try {
            if ($token = app('request')->header('token', input('token', ''))) {
                list($name, $value) = explode('=', Crypt::decode($token));
                if (!empty($value) && session_name() === $name) {
                    session_id($value);
                }
            }
        } catch (\Exception $e) {
            Log::error(__METHOD__ . " : {$e->getMessage()}");
        }
    }

    /**
     * Ajax跨域Options授权处理
     */
    public static function optionsHandler()
    {
        self::setSessionToken();
        if (request()->isOptions()) {
            header('Access-Control-Allow-Credentials:true');
            header('Access-Control-Allow-Methods:GET,POST,OPTIONS');
            header('Access-Control-Allow-Origin:' . app('request')->header('origin', '*'));
            header('Access-Control-Allow-Headers:Accept,Referer,Host,Keep-Alive,User-Agent,X-Requested-With,Cache-Control,Cookie,token');
            header('Content-Type:text/plain charset=UTF-8');
            header('Access-Control-Max-Age:1728000');
            header('HTTP/1.0 204 No Content');
            header('Content-Length:0');
            header('status:204');
            exit;
        }
    }

    /**
     * Ajax跨域Request头部信息
     * @return array
     */
    public static function getRequestHeader()
    {
        return [
            'Access-Control-Allow-Credentials' => "true",
            'Access-Control-Allow-Methods'     => 'GET,POST,OPTIONS',
            'Access-Control-Allow-Origin'      => app('request')->header('origin', '*'),
            'Access-Control-Allow-Headers'     => 'Accept,Referer,Host,Keep-Alive,User-Agent,X-Requested-With,Cache-Control,Cookie,token',
        ];
    }

}