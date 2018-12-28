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

namespace app\service\handler;

use app\service\logic\Wechat;

/**
 * 微信网页授权接口
 * Class WechatHandler
 * @package app\wechat\handler
 * @author Anyon <zoujingli@qq.com>
 */
class WechatHandler extends BasicHandler
{
    /**
     * 获取公众号的配置参数
     * @param string $name 参数名称
     * @return array|string
     * @throws \think\Exception
     */
    public function config($name = null)
    {
        $this->checkInit();
        return Wechat::WeChatScript($this->appid)->config->get($name);
    }

    /**
     * 微信网页授权
     * @param string $sessid 当前会话id(可用session_id()获取)
     * @param string $self_url 当前会话URL地址(需包含域名的完整URL地址)
     * @param int $fullMode 网页授权模式(0静默模式,1高级授权)
     * @return array|bool
     * @throws \think\Exception
     */
    public function oauth($sessid, $self_url, $fullMode = 0)
    {
        $this->checkInit();
        $fans = cache("{$this->appid}_{$sessid}_fans");
        $openid = cache("{$this->appid}_{$sessid}_openid");
        if (!empty($openid) && (empty($fullMode) || !empty($fans))) {
            return ['openid' => $openid, 'fans' => $fans, 'url' => ''];
        }
        $service = Wechat::service();
        $mode = empty($fullMode) ? 'snsapi_base' : 'snsapi_userinfo';
        $url = url('@service/api.push/oauth', '', true, true);
        $params = ['mode' => $fullMode, 'sessid' => $sessid, 'enurl' => encode($self_url)];
        $authurl = $service->getOauthRedirect($this->appid, $url . '?' . http_build_query($params), $mode);
        return ['openid' => $openid, 'fans' => $fans, 'url' => $authurl];
    }

    /**
     * 微信网页JS签名
     * @param string $url 当前会话URL地址(需包含域名的完整URL地址)
     * @return array|boolean
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     */
    public function jsSign($url)
    {
        $this->checkInit();
        return Wechat::WeChatScript($this->appid)->getJsSign($url);
    }

}