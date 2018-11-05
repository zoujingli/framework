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
     * @param boolean $safe
     * @return boolean
     */
    public function has($name, $safe = false)
    {
        return file_exists($this->path($name, $safe));
    }

    /**
     * 根据Key读取文件内容
     * @param string $name
     * @param boolean $safe
     * @return string
     */
    public function get($name, $safe = false)
    {
        $file = $this->path($name, $safe);
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
        $base = rtrim(dirname(request()->basefile()), '\\/');
        return "{$base}/upload/{$name}";
    }

    /**
     * 文件储存在本地
     * @param string $name
     * @param string $content
     * @param boolean $safe
     * @return array|null
     */
    public function save($name, $content, $safe = false)
    {
        try {
            $file = $this->path($name, $safe);
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
     * @param boolean $safe
     * @return string
     */
    public function path($name, $safe = false)
    {
        $path = $safe ? 'safefile' : 'public/upload';
        return str_replace('\\', '/', env('root_path') . "{$path}/{$name}");
    }

    /**
     * 获取文件信息
     * @param string $name
     * @param boolean $safe
     * @return array|null
     */
    public function info($name, $safe = false)
    {
        if ($this->has($name, $safe)) {
            $file = $this->path($name, $safe);
            return ['file' => $file, 'key' => "upload/{$name}", 'hash' => md5_file($file), 'url' => $this->base($name)];
        }
        return null;
    }

}