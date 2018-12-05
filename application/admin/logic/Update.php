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

        $root = str_replace('\\', '/', env('root_path'));
        foreach ($dirs as $key => $dir) {
            $dirs[$key] = str_replace('\\', '/', $dir);
            $maps = array_merge($maps, self::scanDir("{$root}{$dirs[$key]}", [], $root));
        }
        foreach ($files as $key => $file) {
            $files[$key] = str_replace('\\', '/', $file);
            $maps = array_merge($maps, self::scanFile("{$root}{$files[$key]}", [], $root));
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
        // 数据扁平化
        list($_one, $_two) = [[], []];
        foreach ($two as $t) $_two[$t['name']] = $t;
        foreach ($one as $t) $_one[$t['name']] = $t;
        // 数据差异计算
        foreach ($one as $k => $o) {
            if (isset($_two[$o['name']])) {
                $one[$k]['local_name'] = $_two[$o['name']]['name'];
                $one[$k]['local_hash'] = $_two[$o['name']]['hash'];
                $one[$k]['local_time'] = $_two[$o['name']]['time'];
                $one[$k]['local_size'] = $_two[$o['name']]['size'];
                if ($o['hash'] === $_two[$o['name']]['hash']) {
                    $one[$k]['type'] = null;
                } else {
                    $one[$k]['type'] = 'modify';
                }
            } else {
                $one[$k]['type'] = 'delete';
            }
        }
        // 数据增量计算
        foreach ($two as $o) if (!isset($_one[$o['name']])) $one[] = array_merge($o, ['type' => 'add']);
        usort($one, function ($a, $b) {
            return $a['name'] <> $b['name'] ? ($a['name'] > $b['name'] ? 1 : -1) : 0;
        });
        return $one;
    }

    /**
     * 获取目录文件列表
     * @param string $dir 待扫描的目录
     * @param array $data 扫描的结果
     * @param string $root 应用根目录
     * @return array
     */
    private static function scanDir($dir, $data = [], $root = '')
    {
        foreach (scandir($dir) as $sub) if (strpos($sub, '.') !== 0) {
            if (is_dir($temp = str_replace('\\', '/', $dir . DIRECTORY_SEPARATOR . $sub))) {
                $data = array_merge($data, self::scanDir($temp, [], $root));
            } else {
                $name = str_replace($root, '', $temp);
                $data[$name] = ['hash' => self::hash($temp), 'name' => $name, 'time' => filemtime($temp), 'size' => filesize($temp)];
            }
        }
        return $data;
    }

    /**
     * 扫描文件信息
     * @param string $file 待处理的文件
     * @param array $data 扫描的结果
     * @param string $root 应用根目录
     * @return array
     */
    private static function scanFile($file, $data = [], $root = '')
    {
        if (file_exists($file)) {
            $name = str_replace($root, '', str_replace('\\', '/', $file));
            $data[$name] = ['hash' => self::hash($file), 'name' => $name, 'time' => filemtime($file), 'size' => filesize($file)];
        }
        return $data;
    }

    /**
     * 计算文件hash值
     * @param string $file
     * @return string
     */
    private static function hash($file)
    {
        $content = preg_replace('/\s{1,}/', '', file_get_contents($file));
        return md5($content);
    }

}