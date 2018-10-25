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
 * 文件管理逻辑
 * Class File
 * @package app\admin\logic
 * @method array save($name, $content) static 保存二进制文件
 * @method string get($name) static 获取文件二进制内容
 * @method string url($name) static 获取文件对应地址
 * @method string base($name = '') static 获取文件存储基础目录
 * @method string upload($client = false) static 获取文件上传推送地址
 * @method boolean has($name) static 判断文件上否已经上传
 * @method string setBucket($name) static 动态创建指定bucket
 */
class File
{
    /**
     * 对象缓存器
     * @var array
     */
    protected static $instance = [];

    /**
     * 静态魔术方法
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \think\Exception
     */
    public static function __callStatic($name, $arguments)
    {
        $class = self::instance(sysconf('storage_type'));
        if (method_exists($class, $name)) return call_user_func_array([$class, $name], $arguments);
        throw new \think\Exception("File driver method not exists: " . get_class($class) . "->{$name}");
    }

    /**
     * 设置文件驱动名称
     * @param string $name
     * @return \app\admin\logic\driver\Local
     * @throws \think\Exception
     */
    public static function instance($name)
    {
        $class = ucfirst(strtolower($name));
        if (!isset(self::$instance[$class])) {
            if (class_exists($object = __NAMESPACE__ . "\\driver\\{$class}")) {
                return self::$instance[$class] = new $object;
            }
            throw new \think\Exception("File driver [{$class}] does not exist.");
        }
        return self::$instance[$class];
    }

    /**
     * 根据文件后缀获取文件MINE
     * @param array $ext 文件后缀
     * @param array $mine 文件后缀MINE信息
     * @return string
     */
    public static function mine($ext, $mine = [])
    {
        $mines = self::mines();
        foreach (is_string($ext) ? explode(',', $ext) : $ext as $e) {
            $mine[] = isset($mines[strtolower($e)]) ? $mines[strtolower($e)] : 'application/octet-stream';
        }
        return join(',', array_unique($mine));
    }

    /**
     * 获取所有文件扩展的mine
     * @return mixed
     */
    public static function mines()
    {
        $mines = cache('all_ext_mine');
        if (empty($mines)) {
            $content = file_get_contents('http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types');
            preg_match_all('#^([^\s]{2,}?)\s+(.+?)$#ism', $content, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) foreach (explode(" ", $match[2]) as $ext) $mines[$ext] = $match[1];
            cache('all_ext_mine', $mines);
        }
        return $mines;
    }


    /**
     * 获取文件相对名称
     * @param string $url 文件链接
     * @param string $ext 文件后缀
     * @param string $pre 文件前缀（若有值需要以/结尾）
     * @return string
     */
    public static function name($url, $ext = '', $pre = '')
    {
        empty($ext) && $ext = strtolower(pathinfo($url, 4));
        return $pre . join('/', str_split(md5($url), 16)) . '.' . ($ext ? $ext : 'tmp');
    }

    /**
     * 下载文件到本地
     * @param string $url 文件URL地址
     * @param boolean $force 是否强制重新下载文件
     * @return array
     */
    public static function down($url, $force = false)
    {
        try {
            $class = self::instance('local');
            $name = self::name($url, '', 'download/');
            if (false === $force && ($siteUrl = $class->url($name))) {
                $file = env('root_path') . "public/upload/{$name}";
                return [
                    'file' => $file, 'key' => "upload/{$name}",
                    'hash' => md5_file($file), 'url' => $siteUrl,
                ];
            }
            return $class->save($name, file_get_contents($url));
        } catch (\Exception $e) {
            \think\facade\Log::error("FileService 文件下载失败 [ {$url} ] {$e->getMessage()}");
        }
        return ['url' => $url];
    }
}