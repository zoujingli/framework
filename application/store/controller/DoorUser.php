<?php

namespace app\store\controller;

use library\Controller;
use think\Db;

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
        $this->title = '门店员工管理';
        $subsql = $this->_query('StoreDoor a')->field('a.id')->like('a.name|door_name')->equal('a.status|door_status')->db()->buildSql();
        return $this->_query($this->table)->whereRaw("id in $subsql")->like('title,phone')->equal('status')->where(['is_deleted' => '0'])->order('id desc')->page();
    }

    /**
     * 数据列表处理
     * @param array $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function _page_filter(array &$data)
    {
        $doors = Db::name('StoreDoor')->where([['id', 'in', array_unique(array_column($data, 'id'))]])->select();
        foreach ($data as &$vo) {
            $vo['door'] = [];
            foreach ($doors as $door) if ($vo['door_id'] === $door['id']) $vo['door'] = $door;
        }
    }

    /**
     * 删除用户
     */
    public function del()
    {
        $this->_delete($this->table);
    }

    /**
     * 用户审核
     */
    public function pass()
    {
        $this->_save($this->table);
    }

}