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

namespace app\admin\command\task;


use app\admin\command\Task;

/**
 * Class TaskStart
 * @package app\admin\command\task
 */
class TaskStart extends Task
{
    /**
     * 配置入口
     */
    protected function configure()
    {
        $this->setName('xtask:start')->setDescription('System message queue starting');
    }

    /**
     * 执行指令
     * @param \think\console\Input $input
     * @param \think\console\Output $output
     * @return int|void|null
     */
    protected function execute(\think\console\Input $input, \think\console\Output $output)
    {
        $cmd = str_replace('\\', '/', PHP_BINARY . ' ' . env('ROOT_PATH') . 'think queue:listen');
        if ($this->checkProcess($cmd)) {
            $output->info('The message queue daemon already exists!');
        } else {
            $this->createProcess($cmd);
            if ($this->checkProcess($cmd)) {
                $output->info('Message queue daemon created successfully!');
            } else {
                $output->error('Message queue daemon creation failed, try again later!');
            }
        }
    }

}