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
 * 抽奖活动配置
 * Class LuckdrawConfig
 * @package app\activity\controller
 */
class LuckdrawConfig extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'ActivityLuckdrawConfig';

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
        $this->title = '抽奖活动管理';
        return $this->_query($this->table)->page();
    }

    public function add()
    {
        return $this->_form($this->table, 'form');
    }

    public function edit()
    {
        return $this->_form($this->table, 'form');
    }

    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    public function del()
    {
        $this->_delete($this->table);
    }

}