<?php

// +----------------------------------------------------------------------
// | framework
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://framework.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/framework
// +----------------------------------------------------------------------

namespace app\wechat\logic;

/**
 * 微信处理管理
 * Class Wechat
 * @package app\wechat\logic
 */
class Wechat extends \We
{

    /**
     * 获取微信支付配置
     * @param array|null $options
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function config($options = null)
    {
        if (empty($options)) $options = [
            'token'          => sysconf('wechat_token'),
            'appid'          => sysconf('wechat_appid'),
            'appsecret'      => sysconf('wechat_appsecret'),
            'encodingaeskey' => sysconf('wechat_encodingaeskey'),
            'mch_id'         => sysconf('wechat_mch_id'),
            'mch_key'        => sysconf('wechat_mch_key'),
            'cachepath'      => env('runtime_path') . 'wechat' . DIRECTORY_SEPARATOR,
        ];
        if (sysconf('wechat_mch_ssl_type') === 'p12') {
            $options['ssl_p12'] = self::_parseCertPath(sysconf('wechat_mch_ssl_p12'));
        } else {
            $options['ssl_key'] = self::_parseCertPath(sysconf('wechat_mch_ssl_key'));
            $options['ssl_cer'] = self::_parseCertPath(sysconf('wechat_mch_ssl_cer'));
        }
        return parent::config($options);
    }

    /**
     * 解析证书路径
     * @param string $path
     * @return mixed
     * @throws \think\Exception
     */
    private static function _parseCertPath($path)
    {
        if (preg_match('|^[a-z0-9]{16,16}\/[a-z0-9]{16,16}\.|i', $path)) {
            return \library\File::instance('local')->path($path, true);
        }
        return $path;
    }

    /**
     * 静态魔术加载方法
     * @param string $name 静态类名
     * @param array $arguments 参数集合
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @throws \WeChat\Exceptions\InvalidInstanceException
     */
    public static function __callStatic($name, $arguments)
    {
        if (substr($name, 0, 6) === 'WeChat') {
            $class = 'WeChat\\' . substr($name, 6);
        } elseif (substr($name, 0, 6) === 'WeMini') {
            $class = 'WeMini\\' . substr($name, 6);
        } elseif (substr($name, 0, 5) === 'WePay') {
            $class = 'WePay\\' . substr($name, 5);
        } elseif (substr($name, 0, 6) === 'AliPay') {
            $class = 'AliPay\\' . substr($name, 6);
        }
        if (!empty($class) && class_exists($class)) {
            $option = array_shift($arguments);
            return new $class(is_array($option) ? $option : self::config());
        }
        throw new \WeChat\Exceptions\InvalidInstanceException("class {$name} not found");
    }

    /**
     * 获取当前公众号APPID
     * @return bool|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function getAppid()
    {
        return sysconf('wechat_appid');
    }

}