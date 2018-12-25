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

namespace app\admin\logic;

use think\console\Output;
use think\Db;
use think\queue\Job;

/**
 * 任务管理器
 * Class Queue
 * @package app\admin\logic
 */
class Queue
{
    // 待处理
    const STATUS_PEND = 1;
    // 处理中
    const STATUS_PROC = 2;
    // 处理完成
    const STATUS_COMP = 3;
    // 处理失败
    const STATUS_FAIL = 4;

    /**
     * 任务ID
     * @var integer
     */
    protected $id;

    /**
     * 任务数据
     * @var array
     */
    protected $data;

    /**
     * 任务名称
     * @var string
     */
    protected $title;

    /**
     * 命令输出对象
     * @var Output
     */
    protected $output;

    /**
     * 任务状态
     * @var integer
     */
    protected $status;

    /**
     * 任务状态描述
     * @var string
     */
    protected $statusDesc = '';

    /**
     * 创建任务并记录日志
     * @param string $title 任务名称
     * @param string $uri 任务命令
     * @param integer $later 延时时间
     * @param array $data 任务附加数据
     * @param integer $double 任务多开
     * @param string $desc 任务描述
     * @throws \think\Exception
     */
    public static function add($title, $uri, $later, array $data, $double = 1, $desc = '')
    {
        if (empty($double) && self::exists($title)) {
            throw new \think\Exception('该任务已经创建，请耐心等待处理完成！');
        }
        $job_id = Db::name('SystemJobsLog')->insertGetId([
            'title' => $title, 'later' => $later, 'uri' => $uri, 'double' => intval($double),
            'data'  => json_encode($data, 256), 'desc' => $desc, 'status_at' => date('Y-m-d H:i:s'),
        ]);
        $data['_job_id_'] = $job_id;
        $data['_job_title_'] = $title;
        \think\Queue::later($later, $uri, $data);
    }

    /**
     * 更新任务状态
     * @param integer $jobId
     * @param integer $status
     * @param string $statusDesc
     * @return boolean
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function status($jobId, $status = self::STATUS_PEND, $statusDesc = '')
    {
        $result = Db::name('SystemJobsLog')->where(['id' => $jobId])->update([
            'status' => $status, 'status_desc' => $statusDesc, 'status_at' => date('Y-m-d H:i:s'),
        ]);
        return $result !== false;
    }

    /**
     * 检查任务是否存在
     * @param string $title
     * @return boolean
     */
    public static function exists($title)
    {
        $where = [['title', 'eq', $title], ['status', 'in', [1, 2]]];
        return Db::name('SystemJobsLog')->where($where)->count() > 0;
    }

    /**
     * 获取任务数据
     * @param integer $jobId
     * @return array|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function get($jobId)
    {
        return Db::name('SystemJobsLog')->where(['id' => $jobId])->find();
    }

    /**
     * 删除任务数据
     * @param integer $jobId
     * @return boolean
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function del($jobId)
    {
        $where = [['id', 'eq', $jobId], ['status', 'in', [1, 3, 4]]];
        if (Db::name('SystemJobsLog')->where($where)->delete() > 0) {
            Db::name('SystemJobs')->whereLike('payload', '%"_job_id_":"' . $jobId . '"%')->delete();
            return true;
        }
        return false;
    }

    /**
     * 启动任务处理
     * @param Job $job
     * @param array $data
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function fire(Job $job, $data)
    {
        $this->data = $data;
        $this->output = new Output();
        $this->id = isset($data['_job_id_']) ? $data['_job_id_'] : '';
        $this->title = isset($data['_job_title_']) ? $data['_job_title_'] : '';
        // 标记任务处理中
        $this->writeln('执行任务开始...');
        Queue::status($this->id, Queue::STATUS_PROC, $this->statusDesc);
        if ($this->execute()) {
            $this->writeln('执行任务完成！');
            $this->status = Queue::STATUS_COMP;
        } else {
            $this->writeln('执行任务失败！');
            $this->status = Queue::STATUS_FAIL;
        }
        $job->delete();
        Queue::status($this->id, $this->status, $this->statusDesc);
        Message::add("{$this->title}", '任务执行完成', url('@admin/queue/index'), 'admin/queue/index');
    }

    /**
     * 输出消息
     * @param string $text
     * @param string $method
     */
    protected function writeln($text, $method = 'writeln')
    {
        $this->output->$method("【({$this->id}){$this->title}】{$text} {$this->statusDesc}");
    }

    /**
     * 执行任务
     * @return boolean
     */
    protected function execute()
    {
        return true;
    }

}