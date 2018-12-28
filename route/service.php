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

return [
    'wechat/api.push/ticket'        => 'service/api.push/ticket',
    'wechat/api.push/notify/:appid' => 'service/api.push/notify',
    'wechat/api.client/yar/:param'  => 'service/api.client/yar',
    'wechat/api.client/soap/:param' => 'service/api.client/soap',
];