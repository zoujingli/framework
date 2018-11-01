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

namespace app\wechat\controller;

use library\Controller;

/**
 * 模板配置
 * Class Config
 * @package app\wechat\controller
 */
class Config extends Controller
{
    /**
     * 公众号授权绑定
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function options()
    {
        $this->title = '公众号授权绑定';
        if ($this->request->isGet()) return $this->fetch();
        foreach ($this->request->post() as $k => $v) sysconf($k, $v);
        $this->success('公众号参数获取成功！');
    }

    /**
     * 公众号支付配置
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function payment()
    {
        $this->title = '公众号支付配置';
        if ($this->request->isGet()) return $this->fetch();
        foreach ($this->request->post() as $k => $v) sysconf($k, $v);
        $this->success('公众号支付配置成功！');
    }

}