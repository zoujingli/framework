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

namespace app\activity\controller;

use library\Controller;

/**
 * 奖品领取记录
 * Class LuckdrawMemberRecord
 * @package app\activity\controller
 */
class LuckdrawMemberRecord extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'ActivityLuckdrawMemberRecord';

    /**
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        return $this->_query($this->table)->page();
    }

}