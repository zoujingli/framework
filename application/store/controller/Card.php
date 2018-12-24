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
 * 卡券管理
 * Class Card
 * @package app\store\controller
 */
class Card extends Controller
{

    /**
     * 指定当前数据表
     * @var string
     */
    protected $table = 'StoreCard';

    /**
     * 设定卡券类型
     * @var array
     */
    public $types = ['服务券', '产品券'];


    /**
     * 卡券列表
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '卡券管理';
        return $this->_query($this->table)->where(['is_deleted' => '0'])->order('sort asc,id desc')->page();
    }

    public function add()
    {
        return $this->_form($this->table, 'form');

    }

    public function edit()
    {
        return $this->_form($this->table, 'form');
    }

    /**
     * 删除卡券
     */
    public function del()
    {
        $this->_delete($this->table);
    }

    /**
     * 卡券禁用
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 卡券禁用
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

}