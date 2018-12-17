<?php

namespace app\store\controller;

use library\Controller;

/**
 * 门店管理
 * Class Door
 * @package app\store\controller
 */
class Door extends Controller
{
    /**
     * 指定当前数据表
     * @var string
     */
    protected $table = 'StoreDoor';

    /**
     * 门店列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '门店管理';
        return $this->_query($this->table)->equal('status')->like('title')->where(['is_deleted' => '0'])->order('sort asc,id desc')->page();
    }

    /**
     * 添加门店
     * @return mixed
     */
    public function add()
    {
        return $this->_form($this->table, 'form');

    }

    /**
     * 编辑门店
     * @return mixed
     */
    public function edit()
    {
        return $this->_form($this->table, 'form');
    }

    /**
     * 删除门店
     */
    public function del()
    {
        $this->_delete($this->table);
    }

    /**
     * 门店禁用
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 门店禁用
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }


}