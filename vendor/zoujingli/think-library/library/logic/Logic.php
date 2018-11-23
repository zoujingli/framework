<?php

// +----------------------------------------------------------------------
// | Library for ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://library.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github 仓库地址 ：https://github.com/zoujingli/ThinkLibrary
// +----------------------------------------------------------------------

namespace library\logic;

use think\Db;
use think\db\Query;
use library\Controller;

/**
 * 基础视图管理器
 * Class Logic
 * @package library\view
 */
abstract class Logic
{
    /**
     * 数据库操作对象
     * @var \think\db\Query
     */
    protected $db;

    /**
     * 当前请求对象
     * @var \think\Request
     */
    protected $request;

    /**
     * 当前操作控制器引用
     * @var \library\Controller
     */
    protected $controller;

    /**
     * View constructor.
     * @param string|Query $dbQuery
     */
    public function __construct($dbQuery)
    {
        $this->request = app('request');
        $this->db = is_string($dbQuery) ? Db::name($dbQuery) : $dbQuery;
    }

    /**
     * 魔术调用函数
     * @param string $name 函数名称
     * @param array $arguments 调用参数
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        }
    }

    abstract protected function init(Controller $controller);

}