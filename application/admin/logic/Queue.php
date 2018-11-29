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
    /**
     * 待处理
     */
    const JOBS_PENDING = 1;

    /**
     * 处理中
     */
    const JOBS_PROCESSING = 2;

    /**
     * 处理完成
     */
    const JOBS_COMPLETE = 3;

    /**
     * 处理失败
     */
    const JOBS_FAIL = 4;

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
     * 状态描述
     * @var string
     */
    protected $statusDesc = '';

    /**
     * 创建任务并记录日志
     * @param string $title 任务名称
     * @param string $uri 任务命令
     * @param integer $later 延时时间
     * @param array $data 任务附加数据
     * @param string $desc 任务描述
     */
    public static function add($title, $uri, $later, array $data, $desc = '')
    {
        $job_id = Db::name('SystemJobsLog')->insertGetId([
            'title' => $title, 'later' => $later, 'uri' => $uri,
            'data'  => json_encode($data, 256), 'desc' => $desc,
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
    public static function status($jobId, $status = self::JOBS_PENDING, $statusDesc = '')
    {
        $result = Db::name('SystemJobsLog')->where(['id' => $jobId])->update([
            'status' => $status, 'status_desc' => $statusDesc,
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
        $this->writeln('执行任务开始');
        Queue::status($this->id, Queue::JOBS_PENDING, $this->statusDesc);
        if ($this->execute()) {
            $job->delete();
            $this->writeln('执行任务完成');
            Queue::status($this->id, Queue::JOBS_COMPLETE, $this->statusDesc);
        }
        if ($job->attempts() > 3) {
            $job->delete();
            $this->writeln('执行任务失败');
            Queue::status($this->id, Queue::JOBS_FAIL, $this->statusDesc);
        }
    }

    /**
     * 输出消息
     * @param string $text
     */
    protected function writeln($text)
    {
        $this->output->writeln("【{$this->title}】{$text}");
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