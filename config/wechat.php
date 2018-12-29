<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://think.ctolog.com
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/ThinkAdmin
// +----------------------------------------------------------------------

\think\facade\Route::rules([
    // 发起开放平台授权链接兼容
    'wechat/api.push/auth/:redirect'  => 'service/api.push/auth',
    'service/api.push/auth/:redirect' => 'service/api.push/auth',
    // 微信开放平台推送兼容
    'wechat/api.push/ticket'          => 'service/api.push/ticket',
    'service/api.push/ticket'         => 'service/api.push/ticket',
    'wechat/api.push/notify/:appid'   => 'service/api.push/notify',
    'service/api.push/notify/:appid'  => 'service/api.push/notify',
    // 微信开放平台与客户端接口兼容
    'wechat/api.client/soap/:param'   => 'service/api.client/soap',
    'service/api.client/soap/:param'  => 'service/api.client/soap',
]);

return [
    // ThinkService 授权接口地址
    'service_url' => 'https://framework.thinkadmin.top',
];