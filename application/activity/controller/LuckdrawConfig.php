<?php

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
        return $this->_query($this->table)->page();
    }

}