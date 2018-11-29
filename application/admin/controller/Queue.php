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

namespace app\admin\controller;

use library\Controller;

/**
 * Class Queue
 * @package app\admin\controller
 */
class Queue extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'SystemJobsLog';

    /**
     * 任务列表
     * @return array
     */
    public function index()
    {
        $this->title = '系统任务管理';
        $query = $this->_query($this->table)->like('title');
        return $this->_page($query->db()->order('id desc'));
    }

}