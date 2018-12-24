<?php

namespace app\admin\controller;

use library\Controller;

/**
 * 系统消息管理
 * Class Message
 * @package app\admin\controller
 */
class Message extends Controller
{
    /**
     * 指定数据表
     * @var string
     */
    protected $table = 'SystemMessage';

    /***
     * 获取消息列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '系统消息管理';
        return $this->_query($this->table)->like('title,desc')->equal('read_state')->dateBetween('create_at,read_at')->order('id desc')->page();
    }

    /**
     * 消息状态
     */
    public function state()
    {
        $this->_save($this->table, ['read_state' => '1', 'read_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * 删除消息
     */
    public function del()
    {
        $this->_delete($this->table);
    }

}