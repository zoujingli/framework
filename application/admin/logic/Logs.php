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
use think\db\Query;
use think\facade\Request;

/**
 * Sqlite日志管理器
 * Class Logs
 * @package app\admin\logic
 */
class Logs
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
        $file = env('runtime_path') . 'logs.db';
        if (!empty(self::$db)) return self::$db;
        if (!file_exists($file)) touch($file);
        $db = Db::connect(['debug' => true, 'type' => 'sqlite', 'database' => $file]);
        $db->query(self::initTableSql());
        return self::$db = $db;
    }

    /**
     * @param string $type
     * @param string $content
     * @throws \think\Exception
     */
    public static function write($type, $content)
    {
        self::db()->name('logs')->insert([
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