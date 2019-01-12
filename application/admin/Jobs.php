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

namespace app\admin;

use app\admin\service\Message;
use app\admin\service\Queue;
use think\console\Output;
use think\queue\Job;

/**
 * 基础指令公共类
 * Class QueueBase
 * @package app\admin\command
 */
class Jobs
{
    # 待处理
    const STATUS_PEND = 1;
    # 处理中
    const STATUS_PROC = 2;
    # 处理完成
    const STATUS_COMP = 3;
    # 处理失败
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
     * 任务状态
     * @var integer
     */
    protected $status;

    /**
     * @var Output
     */
    protected $output;

    /**
     * 任务状态描述
     * @var string
     */
    protected $statusDesc = '';

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
        $this->writeln('Queue starting ...');
        Queue::status($this->id, Queue::STATUS_PROC, $this->statusDesc);
        if ($this->execute()) {
            $this->writeln('Queue execution completion.');
            $this->status = Jobs::STATUS_COMP;
        } else {
            $this->writeln('Queue execution failure.');
            $this->status = Jobs::STATUS_FAIL;
        }
        $job->delete();
        Queue::status($this->id, $this->status, $this->statusDesc);
        Message::add("{$this->title}", '后台任务执行完成！', url('@admin/queue/index'), 'admin/queue/index');
    }

    /**
     * 过程消息输出
     * @param string $text 消息内容
     * @param string $method 输出方法
     */
    protected function writeln($text, $method = 'writeln')
    {
        $message = "【({$this->id}){$this->title}】{$text} {$this->statusDesc}";
        if (method_exists($this->output, $method)) $this->output->$method($message);
        else $this->output->writeln($message);
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