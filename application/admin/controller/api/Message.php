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

namespace app\admin\controller\api;

use library\Controller;
use think\Db;

/**
 * Class Message
 * @package app\admin\controller\api
 */
class Message extends Controller
{
    /**
     * 获取系统消息列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function gets()
    {
        $where = ['read_state' => '0'];
        $list = Db::name('SystemMessage')->where($where)->order('id desc')->select();
        $this->success('获取系统消息成功！', $list);
    }

}