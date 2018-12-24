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

use think\Db;

/**
 * 系统消息管理
 * Class Message
 * @package app\admin\logic
 */
class Message
{
    /**
     * 增加系统消息
     * @param string $title 消息标题
     * @param string $desc 消息描述
     * @param string $url 访问链接
     * @param string $node 权限节点
     * @return boolean
     */
    public static function add($title, $desc, $url, $node)
    {
        $data = ['title' => $title, 'desc' => $desc, 'url' => $url, 'node' => $node];
        return Db::name('SystemMessage')->insert($data) !== false;
    }

}