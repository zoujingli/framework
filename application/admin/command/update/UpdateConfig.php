<?php
/**
 * Created by PhpStorm.
 * User: Anyon
 * Date: 2019/1/14
 * Time: 9:42
 */

namespace app\admin\command\update;


use app\admin\command\Update;

class UpdateConfig extends Update
{
    /**
     * 配置入口
     */
    protected function configure()
    {
        $this->modules = ['config/'];
        $this->setName('update:config')->setDescription('Sync Update Config Files for ThinkAdmin');
    }

}