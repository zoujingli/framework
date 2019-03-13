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

namespace app\admin\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

/**
 * 消息队列守护进程管理
 * Class Task
 * @package app\admin\command
 */
class Task extends Command
{
    /**
     * 配置入口
     */
    protected function configure()
    {
        $this->setName('xrun:task')->setDescription('System message queue checking');
    }

    /**
     * 执行指令
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    protected function execute(Input $input, Output $output)
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

    /**
     * 创建消息任务进程
     * @param string $cmd
     */
    private function createProcess($cmd)
    {
        if ($this->isWin()) {
            shell_exec('wmic process call create "' . $cmd . '"');
        } else {
            $process = proc_open("{$cmd} &", [["pipe", "r"], ["pipe", "w"], ["pipe", "w"]], $pipes);
            if (is_resource($process)) {
                fclose($pipes[0]);
                fclose($pipes[1]);
                proc_close($process);
            }
        }
    }

    /**
     * 检查进程是否存在
     * @param string $cmd
     * @return boolean
     */
    private function checkProcess($cmd)
    {
        if ($this->isWin()) {
            $result = shell_exec('wmic process where name="php.exe" get CommandLine');
        } else {
            $result = shell_exec('ps aux|grep -v grep|grep "' . $cmd . '"');
        }
        return stripos(str_replace('\\', '/', $result), $cmd) !== false;
    }

    /**
     * 判断系统类型
     * @return boolean
     */
    private function isWin()
    {
        return PATH_SEPARATOR == ';';
    }

}