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

namespace app\admin\command\update;

use app\admin\command\Update;

/**
 * 系统模块更新指令
 * Class UpdateAdmin
 * @package app\admin\logic\update
 */
class UpdateAdmin extends Update
{
    /**
     * 配置入口
     */
    protected function configure()
    {
        $this->modules = ['application/admin/'];
        $this->setName('xupdate:admin')->setDescription('Synchronize update admin module files');
    }
}