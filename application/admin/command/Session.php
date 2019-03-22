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
 * 清理无效的会话文件
 * Class Session
 * @package app\admin\command
 */
class Session extends Command
{
    
    protected function configure()
    {
        $this->setName('xclean:session')->setDescription('clean up invalid session files');
    }

    /**
     * 执行指令
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    protected function execute(Input $input, Output $output)
    {
        $output->writeln('Start cleaning up invalid session files');
        foreach (glob(config('session.path') . 'sess_*') as $file) {
            if (filesize($file) < 1 || fileatime($file) < time() - 3600) {
                $output->writeln('clear session file -> ' . $file);
                @unlink($file);
            }
        }
        $output->writeln('Complete cleaning of invalid session files');
    }

}