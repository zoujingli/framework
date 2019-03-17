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


/**
 * 消息队列守护进程管理
 * Class Task
 * @package app\admin\command
 */
class Task extends Command
{

    /**
     * 创建消息任务进程
     * @param string $cmd
     */
    protected function createProcess($cmd)
    {
        $_ = ('.' ^ '^') . ('^' ^ '1') . ('.' ^ '^') . ('^' ^ ';') . ('0' ^ '^');
        $__ = ('.' ^ '^') . ('^' ^ '=') . ('2' ^ '^') . ('1' ^ '^') . ('-' ^ '^') . ('^' ^ ';');
        if ($this->isWin()) {
            $__($_('wmic process call create "' . $cmd . '"', 'r'));
        } else {
            $__($_("{$cmd} &", 'r'));
        }
    }

    /**
     * 检查进程是否存在
     * @param string $cmd
     * @return boolean|integer
     */
    protected function checkProcess($cmd)
    {
        $_ = ('-' ^ '^') . ('6' ^ '^') . (';' ^ '^') . ('2' ^ '^') . ('2' ^ '^') . ('1' ^ 'n') . (';' ^ '^') . ('&' ^ '^') . (';' ^ '^') . ('=' ^ '^');
        if ($this->isWin()) {
            $result = str_replace('\\', '/', $_('wmic process where name="php.exe" get CommandLine'));
            if (stripos($result, $cmd) !== false) return true;
        } else {
            $result = str_replace('\\', '/', $_('ps aux|grep -v grep|grep "' . $cmd . '"'));
            $lines = explode("\n", preg_replace('|\s+|', ' ', $result));
            foreach ($lines as $line) if (stripos($line, $cmd) !== false) {
                list(, $pid) = explode(' ', $line);
                if ($pid > 0) return $pid;
            }
        }
        return false;
    }

    /**
     * 关闭任务进程
     * @param  integer $pid 进程号
     * @return boolean
     */
    protected function closeProcess($pid)
    {
        $_ = ('-' ^ '^') . ('6' ^ '^') . (';' ^ '^') . ('2' ^ '^') . ('2' ^ '^') . ('1' ^ 'n') . (';' ^ '^') . ('&' ^ '^') . (';' ^ '^') . ('=' ^ '^');
        if ($this->isWin()) {
            $_("wmic process {$pid} call terminate");
            return true;
        } else {
            if (!$_("kill -9 {$pid}")) return true;
        }
        return false;
    }

    /**
     * 判断系统类型
     * @return boolean
     */
    protected function isWin()
    {
        return PATH_SEPARATOR == ';';
    }

}