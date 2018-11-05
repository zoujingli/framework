<?php

// +----------------------------------------------------------------------
// | Library for ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://library.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 仓库地址 ：https://gitee.com/zoujingli/ThinkLibrary
// | github 仓库地址 ：https://github.com/zoujingli/ThinkLibrary
// +----------------------------------------------------------------------

namespace logic;

use think\Db;
use think\db\Query;
use think\facade\Request;

/**
 * Sqlite 日志管理器
 * Class Logs
 * @package logic
 */
class Log
{
    /**
     * 当前数据对象
     * @var Query
     */
    private static $db;

    /**
     * 数据库链接
     * @return Query
     * @throws \think\Exception
     */
    public static function db()
    {
        if (!empty(self::$db)) return self::$db;
        if (!file_exists($file = env('runtime_path') . 'logs.db')) touch($file);
        $db = Db::connect(['debug' => true, 'type' => 'sqlite', 'database' => $file]);
        $db->query(self::initTableSql());
        return self::$db = $db;
    }

    /**
     * 写入日志
     * @param string $type
     * @param string $content
     * @return integer
     * @throws \think\Exception
     */
    public static function write($type, $content)
    {
        return self::db()->name('logs')->insert([
            'type'      => $type,
            'geoip'     => Request::ip(),
            'action'    => Request::path(),
            'content'   => $content,
            'create_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * 获取初始化数据表
     * @return string
     */
    private static function initTableSql()
    {
        return <<<sql
CREATE TABLE IF NOT EXISTS "logs" (
  "id" INTEGER NOT NULL,
  "type" TEXT DEFAULT '',
  "geoip" TEXT DEFAULT '',
  "action" TEXT DEFAULT '',
  "content" TEXT DEFAULT '',
  "create_at" TEXT DEFAULT '',
  PRIMARY KEY ("id")
);
sql;
    }

}