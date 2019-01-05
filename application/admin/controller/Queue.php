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
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '系统任务管理';
        $this->uris = Db::name($this->table)->distinct(true)->column('uri');
        $this->titles = Db::name($this->table)->distinct(true)->column('title');
        return $this->_query($this->table)->equal('status,title,uri')->dateBetween('create_at,status_at')->order('id desc')->page();
    }

    /**
     * 重置失败的任务
     */
    public function redo()
    {
        try {
            $where = ['id' => $this->request->post('id')];
            $info = Db::name($this->table)->where($where)->find();
            if (empty($info)) $this->error('需要重置的任务获取异常！');
            $data = isset($info['data']) ? json_decode($info['data'], true) : '[]';
            \app\admin\logic\Queue::add($info['title'], $info['uri'], $info['later'], $data, $info['double'], $info['desc']);
            $this->success('任务重置成功！', url('@admin') . '#' . url('@admin/queue/index'));
        } catch (\think\exception\HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $e) {
            $this->error("任务重置失败，请稍候再试！<br> {$e->getMessage()}");
        }
    }

    /**
     * 删除系统任务
     */
    public function del()
    {
        try {
            $isNot = false;
            foreach (explode(',', $this->request->post('id', '0')) as $id) {
                if (!\app\admin\logic\Queue::del($id)) $isNot = true;
            }
            $this->success($isNot ? '部分任务删除成功！' : '任务删除成功！');
        } catch (\think\exception\HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $e) {
            $this->error("任务删除失败，请稍候再试！<br> {$e->getMessage()}");
        }
    }

}