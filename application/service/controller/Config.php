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

namespace app\service\controller;

use library\Controller;

/**
 * 开放平台参数配置
 * Class Config
 * @package app\service\controller
 */
class Config extends Controller
{

    /**
     * 定义当前操作表名
     * @var string
     */
    public $table = 'WechatServiceConfig';

    /**
     * 开放平台接口配置
     * @auth true
     * @menu true
     */
    public function index()
    {
        $this->applyCsrfToken('save');
        $this->title = '开放平台接口配置';
        $this->fetch();
    }

    /**
     * 保存平台参数数据
     * @auth true
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function save()
    {
        $this->applyCsrfToken('save');
        if ($this->request->post()) {
            $post = $this->request->post();
            foreach ($post as $k => $v) sysconf($k, $v);
            $this->success('开放平台参数修改成功！');
        } else {
            $this->error('开放平台参数修改失败！');
        }
    }

}
