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

namespace app\store\controller;

use library\Controller;

/**
 * 页面管理
 * Class Page
 * @package app\store\controller
 */
class Page extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'StorePage';

    /**
     * 页面管理
     */
    public function index()
    {
        $this->fetch();
    }

    /**
     * 添加页面
     */
    public function add()
    {
        $this->_form($this->table, 'form');
    }

    /**
     * 编辑页面
     */
    public function edit()
    {
        $this->_form($this->table, 'form');
    }

    /**
     * 禁用页面
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 启用页面
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 删除页面
     */
    public function del()
    {
        $this->_delete($this->table);
    }

}