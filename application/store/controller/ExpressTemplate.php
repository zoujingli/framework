<?php

namespace app\store\controller;

use library\Controller;

/**
 * 邮费模板管理
 * Class ExpressTemplate
 * @package app\store\controller
 */
class ExpressTemplate extends Controller
{
    /**
     * 指定数据表
     * @var string
     */
    protected $table = 'StoreExpressTemplate';

    /**
     * 邮费模板管理
     */
    public function index()
    {
        $this->title = '邮费模板管理';
        $this->_page($this->table);
    }

    /**
     * 添加邮费模板
     */
    public function add()
    {
        $this->title = '添加邮费模板';
        $this->_form($this->table, 'form');
    }

    /**
     * 编辑邮费模板
     */
    public function edit()
    {
        $this->title = '编辑邮费模板';
        $this->_form($this->table, 'form');
    }

    /**
     * 禁用邮费模板
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 启用邮费模板
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 删除邮费模板
     */
    public function remove()
    {
        $this->_delete($this->table);
    }

}