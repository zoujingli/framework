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
 * Class TaskStop
 * @package app\admin\command\task
 */
class TaskStop extends Task
{

    protected function configure()
    {
        $this->setName('xtask:stop')->setDescription('stop message queuing daemon');
    }

    /**
     * @param \think\console\Input $input
     * @param \think\console\Output $output
     */
    protected function execute(\think\console\Input $input, \think\console\Output $output)
    {
        if (($pid = $this->checkProcess()) > 0) {
            $this->closeProcess($pid);
            $output->info("message queue daemon {$pid} closed successfully.");
        } else {
            $output->info('The message queue daemon is not running.');
        }
    }

}