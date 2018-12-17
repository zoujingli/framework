<?php

namespace app\store\controller;

use library\Controller;

/**
 * 用户用户管理
 * Class Door
 * @package app\store\controller
 */
class DoorUser extends Controller
{
    /**
     * 指定当前数据表
     * @var string
     */
    protected $table = 'StoreDoorUser';

    /**
     * 用户用户管理列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '用户员工管理';
        return $this->_query($this->table)->where(['is_deleted' => '0'])->order('sort asc,id desc')->page();
    }

    public function edit()
    {
        return $this->_form($this->table, 'form');
    }

    /**
     * 删除用户
     */
    public function del()
    {
        $this->_delete($this->table);
    }

    /**
     * 用户禁用
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 用户禁用
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

}