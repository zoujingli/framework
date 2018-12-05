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

/**
 * Class Update
 * @package app\admin\logic
 */
class Update
{

    /**
     * 获取文件信息列表
     * @param array $dirs 需要扫描的目录
     * @param array $files 需要扫描的文件
     * @param array $ignores 忽略扫描的文件
     * @param array $maps 扫描结果列表
     * @return array
     */
    public static function get(array $dirs, array $files = [], $ignores = [], $maps = [])
    {
        foreach ($dirs as &$dir) {
            $dir = self::swp($dir);
            $maps = array_merge($maps, self::scanDir($dir));
        }
        foreach ($files as &$file) {
            $file = self::swp($file);
            $maps = array_merge($maps, self::scanFile($file));
        }
        // 清除忽略文件
        foreach ($ignores as &$file) unset($maps[$file]);
        return ['dirs' => $dirs, 'files' => $files, 'ignores' => $ignores, 'list' => array_values($maps)];
    }

    /**
     * 两二维数组对比
     * @param array $one
     * @param array $two
     * @return array
     */
    public static function contrast(array $one = [], array $two = [])
    {
        $_two = [];
        foreach ($two as $t) $_two[$t['name']] = $t;
        foreach ($one as $t) $_one[$t['name']] = $t;
        foreach ($one as $k => $o) {
            $one[$k]['type'] = 'delete';
            if (isset($_two[$o['name']])) {
                $one[$k]['type'] = $o['hash'] === $_two[$o['name']]['hash'] ? null : 'modify';
            }
        }
        foreach ($two as $o) if (!isset($_one[$o['name']])) $one[] = array_merge($o, ['type' => 'add']);
        return $one;
    }

    /**
     * 获取目录文件列表
     * @param string $dir 待扫描的目录
     * @param array $data 扫描的结果
     * @return array
     */
    private static function scanDir($dir, $data = [])
    {
        foreach (scandir($dir) as $sub) if (strpos($sub, '.') !== 0) {
            if (is_dir($temp = self::swp($dir . DIRECTORY_SEPARATOR . $sub))) {
                $data = array_merge($data, self::scanDir($temp));
            } else $data[$temp] = ['name' => $temp, 'hash' => md5_file($temp), 'time' => (string)filemtime($temp)];
        }
        return $data;
    }

    /**
     * 扫描文件信息
     * @param string $file 待处理的文件
     * @param array $data 扫描的结果
     * @return array
     */
    private static function scanFile($file, $data = [])
    {
        if (file_exists($temp = self::swp($file))) {
            $data[$temp] = ['name' => $temp, 'hash' => md5_file($temp), 'time' => (string)filemtime($temp)];
        }
        return $data;
    }

    /**
     * 目录切割
     * @param string $file
     * @return string
     */
    private static function swp($file)
    {
        $root = str_replace('\\', '/', env('root_path'));
        return str_replace($root, '', str_replace('\\', '/', $file));
    }

}