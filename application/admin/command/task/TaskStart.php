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

    protected function configure()
    {
        $this->setName('xtask:start')->setDescription('start message queue daemon');
    }

    /**
     * @param \think\console\Input $input
     * @param \think\console\Output $output
     * @return int|void|null
     */
    protected function execute(\think\console\Input $input, \think\console\Output $output)
    {
        if (($pid = $this->checkProcess()) > 0) {
            $output->info("The message queue daemon {$pid} already exists!");
        } else {
            $this->createProcess();
            if (($pid = $this->checkProcess()) > 0) {
                $output->info("message queue daemon {$pid} created successfully!");
            } else {
                $output->error('message queue daemon creation failed, try again later!');
            }
        }
    }

}