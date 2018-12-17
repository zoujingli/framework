<?php

namespace app\store\controller;

use library\Controller;

/**
 * 团购管理
 * Class Groups
 * @package app\store\controller
 */
class Groups extends Controller
{
    /**
     * 指定当前数据表
     * @var string
     */
    protected $table = 'StoreGroups';

    /**
     * 团购列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '团购管理';
        return $this->_query($this->table)->where(['is_deleted' => '0'])->order('sort asc,id desc')->page();
    }

    /**
     * 添加团购信息
     * @return mixed
     */
    public function add()
    {
        $this->title = '添加团购';
        return $this->_form($this->table, 'form');
    }

    /**
     * 编辑团购信息
     * @return mixed
     */
    public function edit()
    {
        $this->title = '编辑团购';
        return $this->_form($this->table, 'form');
    }

    /**
     * 团购禁用
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 团购禁用
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 删除团购
     */
    public function del()
    {
        $this->_delete($this->table);
    }
}