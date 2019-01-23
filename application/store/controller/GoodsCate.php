<?php

namespace app\store\controller;

use library\Controller;

/**
 * Class GoodsCate
 * @package app\store\controller
 */
class GoodsCate extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'StoreGoodsCate';

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
        $this->title = '商品分类管理';
        return $this->_query($this->table)->order('sort asc,id desc')->page();
    }

    /**
     * 添加商品分类信息
     * @return mixed
     */
    public function add()
    {
        $this->title = '添加商品分类';
        return $this->_form($this->table, 'form');
    }

    /**
     * 编辑商品分类信息
     * @return mixed
     */
    public function edit()
    {
        $this->title = '编辑商品分类';
        return $this->_form($this->table, 'form');
    }

    /**
     * 商品分类禁用
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 商品分类禁用
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 删除商品分类
     */
    public function del()
    {
        $this->_delete($this->table);
    }

}