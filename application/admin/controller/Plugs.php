<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://framework.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/framework
// +----------------------------------------------------------------------

namespace app\admin\controller;

use library\Controller;

/**
 * 后台插件管理
 * Class Plugs
 * @package app\admin\controller
 */
class Plugs extends Controller
{
    /**
     * 系统选择器图标
     * @return mixed
     */
    public function icon()
    {
        $this->title = '图标选择器';
        $this->field = $this->request->get('field', 'icon');
        return $this->fetch();
    }
}