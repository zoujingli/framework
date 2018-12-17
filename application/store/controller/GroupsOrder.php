<?php

namespace app\store\controller;

use library\Controller;

/**
 * 团队订单管理
 * Class GroupsOrder
 * @package app\store\controller
 */
class GroupsOrder extends Controller
{
    /**
     * 指定当前数据表
     * @var string
     */
    protected $table = 'StoreGroupsOrder';

    /**
     * 团购订单列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '团购订单管理';
        return $this->_query($this->table)->where(['is_deleted' => '0'])->order('id desc')->page();
    }
}