<?php

namespace app\wechat\controller\api;

use app\wechat\logic\Wechat;
use library\Controller;
use think\exception\HttpResponseException;

class Js extends Controller
{
    /**
     * @return string
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $url = $this->request->server('http_referer', $this->request->url(true), null);
        $wechat = Wechat::getWebOauthInfo($url, $this->request->get('mode', 1), false);
        $openid = isset($wechat['openid']) ? $wechat['openid'] : '';
        $unionid = isset($wechat['unionid']) ? $wechat['unionid'] : '';
        $configJson = json_encode(Wechat::getWebJssdkSign($url), JSON_UNESCAPED_UNICODE);
        $fansinfoJson = json_encode(isset($wechat['fansinfo']) ? $wechat['fansinfo'] : [], JSON_UNESCAPED_UNICODE);
        return <<<EOF
if(typeof wx==='object'){
    wx.openid="{$openid}";
    wx.unionid="{$unionid}";
    wx.config({$configJson});
    wx.fansinfo={$fansinfoJson};
    wx.ready(function(){
        wx.hideOptionMenu();
        wx.hideAllNonBaseMenuItem();
    });
}
EOF;
    }

}