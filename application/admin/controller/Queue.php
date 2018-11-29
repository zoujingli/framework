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
use think\Db;

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
        $query = $this->_query($this->table)->like('title,uri')->equal('status')->dateBetween('create_at');
        return $this->_page($query->db()->order('id desc'));
    }

    /**
     * 重置已经失败的任务
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function redo()
    {
        $where = ['id' => $this->request->post('id')];
        $info = Db::name($this->table)->where($where)->find();
        if (empty($info)) $this->error('需要重置的任务获取异常！');
        $data = isset($info['data']) ? json_decode($info['data'], true) : '[]';
        \app\admin\logic\Queue::add($info['title'], $info['uri'], $info['later'], $data);
        $this->success('任务重置成功！', url('@admin') . '#' . url('@admin/queue/index'));
    }

}