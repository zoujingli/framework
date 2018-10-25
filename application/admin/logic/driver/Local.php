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

namespace app\admin\logic\driver;

use app\admin\logic\File;

/**
 * 本地文件上传驱动
 * Class Local
 * @package app\admin\logic\driver
 */
class Local extends File
{

    /**
     * 检查文件是否已经存在
     * @param string $name
     * @return boolean
     */
    public function has($name)
    {
        return file_exists($this->path($name));
    }

    /**
     * 根据Key读取文件内容
     * @param string $name
     * @return string
     */
    public function get($name)
    {
        $file = $this->path($name);
        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * 获取文件当前URL地址
     * @param string $name 文件HASH名称
     * @return boolean|string
     */
    public function url($name)
    {
        if ($this->has($name) === false) return false;
        return $this->base($name);
    }

    /**
     * 根据配置获取到本地上传的目标地址
     * @return string
     */
    public function upload()
    {
        return url('@') . '?s=admin/plugs/upload';
    }

    /**
     * 获取服务器URL前缀
     * @param string $name
     * @return string
     */
    public function base($name = '')
    {
        $appRoot = request()->root(true);
        $uriRoot = preg_match('/\.php$/', $appRoot) ? dirname($appRoot) : $appRoot;
        return "{$uriRoot}/upload/{$name}";
    }

    /**
     * 文件储存在本地
     * @param string $name
     * @param string $content
     * @return array|null
     */
    public function save($name, $content)
    {
        try {
            $file = $this->path($name);
            file_exists(dirname($file)) || mkdir(dirname($file), 0755, true);
            if (file_put_contents($file, $content)) return $this->info($name);
        } catch (\Exception $err) {
            \think\facade\Log::error('本地文件存储失败, ' . $err->getMessage());
        }
        return null;
    }

    /**
     * 获取文件路径
     * @param string $name
     * @return string
     */
    public function path($name)
    {
        return str_replace('\\', '/', env('root_path') . "public/upload/{$name}");
    }

    /**
     * 获取文件信息
     * @param string $name
     * @return array|null
     */
    public function info($name)
    {
        if ($this->has($name)) {
            $file = $this->path($name);
            return ['file' => $file, 'key' => "upload/{$name}", 'hash' => md5_file($file), 'url' => $this->base($name)];
        }
        return null;
    }

}