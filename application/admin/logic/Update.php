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

use think\console\Command;
use think\console\Input;
use think\console\Output;

/**
 * 文件比对支持
 * Class Update
 * @package app\admin\logic
 */
class Update extends Command
{

    /**
     * 基础URL地址
     * @var string
     */
    protected static $baseUri = 'https://framework.thinkadmin.top';

    /**
     * 指令初始化配置
     */
    protected function configure()
    {
        // 指令配置
        $this->setName('update')->setDescription('Sync Update Code from THINKADMIN.TOP');
    }

    /**
     * 执行指令
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    protected function execute(Input $input, Output $output)
    {
        $output->writeln('准备更新指定规则的代码文件...');
        foreach (self::diff() as $file) switch ($file['type']) {
            case 'add':
            case 'mod':
                if (self::down(encode($file['name'])))
                    $output->writeln("更新文件 {$file['name']} 成功！");
                else
                    $output->error("更新文件 {$file['name']} 失败！");
                break;
            case 'del':
                if (unlink(realpath(env('root_path') . $file['name'])))
                    $output->writeln("移除文件 {$file['name']} 成功！");
                else
                    $output->error("移除文件 {$file['name']} 失败！");
                break;
        }
        $output->writeln('指定规则的代码文件更新完成！');
    }

    /**
     * 同步所有差异文件
     */
    public static function sync()
    {
        foreach (self::diff() as $file) switch ($file['type']) {
            case 'add':
            case 'mod':
                if (UpdateLogic::down(encode($file['name'])))
                    echo "同步文件 {$file['name']} 成功！" . PHP_EOL;
                else
                    echo "同步文件 {$file['name']} 失败！" . PHP_EOL;
                break;
            case 'del':
                if (unlink(realpath(env('root_path') . $file['name'])))
                    echo "移除文件 {$file['name']} 成功！" . PHP_EOL;
                else
                    echo "移除文件 {$file['name']} 失败！" . PHP_EOL;
                break;
        }
    }

    /**
     * 获取文件信息列表
     * @param array $paths 需要扫描的目录
     * @param array $ignores 忽略扫描的文件
     * @param array $maps 扫描结果列表
     * @return array
     */
    public static function tree(array $paths, array $ignores = [], array $maps = [])
    {
        $root = str_replace('\\', '/', env('root_path'));
        foreach ($paths as $key => $dir) {
            $paths[$key] = str_replace('\\', '/', $dir);
            $maps = array_merge($maps, self::scanDir("{$root}{$paths[$key]}", $root));
        }
        // 清除忽略文件
        foreach ($maps as $key => $map) foreach ($ignores as $ingore) {
            if (stripos($map['name'], $ingore) === 0) unset($maps[$key]);
        }
        return ['paths' => $paths, 'ignores' => $ignores, 'list' => $maps];
    }

    /**
     * 两二维数组对比
     * @param array $serve 线上文件列表信息
     * @param array $local 本地文件列表信息
     * @return array
     */
    public static function contrast(array $serve = [], array $local = [])
    {
        // 数据扁平化
        list($_serve, $_local, $_new) = [[], [], []];
        foreach ($serve as $t) $_serve[$t['name']] = $t;
        foreach ($local as $t) $_local[$t['name']] = $t;
        unset($serve, $local);
        // 线上数据差异计算
        foreach ($_serve as $t) if (isset($_local[$t['name']])) array_push($_new, [
            'type' => $t['hash'] === $_local[$t['name']]['hash'] ? null : 'mod', 'name' => $t['name'],
        ]);
        else array_push($_new, ['type' => 'add', 'name' => $t['name']]);
        // 本地数据增量计算
        foreach ($_local as $t) if (!isset($_serve[$t['name']])) array_push($_new, ['type' => 'del', 'name' => $t['name']]);
        unset($_serve, $_local);
        usort($_new, function ($a, $b) {
            return $a['name'] <> $b['name'] ? ($a['name'] > $b['name'] ? 1 : -1) : 0;
        });
        return $_new;
    }

    /**
     * 下载更新文件内容
     * @param string $encode
     * @return boolean|integer
     */
    public static function down($encode)
    {
        $result = json_decode(http_get(self::$baseUri . "?s=admin/api.update/read/{$encode}"), true);
        if (empty($result['code'])) return false;
        $path = realpath(env('root_path') . decode($encode));
        file_exists(dirname($path)) || mkdir(dirname($path), 0755, true);
        return file_put_contents($path, base64_decode($result['data']['content']));
    }

    /**
     * 获取文件差异数据
     * @return array
     */
    public static function diff()
    {
        $result = json_decode(http_get(self::$baseUri . "?s=/admin/api.update/tree"), true);
        if (empty($result['code'])) return [];
        $new = self::tree($result['data']['paths'], $result['data']['ignores']);
        return self::contrast($result['data']['list'], $new['list']);
    }

    /**
     * 获取目录文件列表
     * @param string $dir 待扫描的目录
     * @param string $root 应用根目录
     * @param array $data 扫描结果
     * @return array
     */
    private static function scanDir($dir, $root = '', $data = [])
    {
        if (file_exists($dir) && is_file($dir)) {
            return [self::getFileInfo($dir, $root)];
        }
        if (file_exists($dir)) foreach (scandir($dir) as $sub) if (strpos($sub, '.') !== 0) {
            if (is_dir($temp = "{$dir}/{$sub}")) {
                $data = array_merge($data, self::scanDir($temp, $root));
            } else array_push($data, self::getFileInfo($temp, $root));
        }
        return $data;
    }

    /**
     * 获取指定文件信息
     * @param string $file 绝对文件名
     * @param string $root
     * @return array
     */
    private static function getFileInfo($file, $root)
    {
        $hash = md5(preg_replace('/\s{1,}/', '', file_get_contents($file)));
        $name = str_replace($root, '', str_replace('\\', '/', realpath($file)));
        return ['name' => $name, 'hash' => $hash];
    }

}