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
use OSS\OssClient;
use think\facade\Log;

/**
 * AliOss文件存储
 * Class Oss
 * @package app\admin\logic\driver
 */
class Oss extends File
{

    /**
     * 检查文件是否已经存在
     * @param string $name
     * @return boolean
     * @throws \OSS\Core\OssException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function has($name)
    {
        list($keyid, $secret) = [sysconf('storage_oss_keyid'), sysconf('storage_oss_secret')];
        $ossClient = new OssClient($keyid, $secret, $this->upload(), true);
        return $ossClient->doesObjectExist(sysconf('storage_oss_bucket'), $name);
    }

    /**
     * 根据Key读取文件内容
     * @param string $name
     * @return string
     * @throws \OSS\Core\OssException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function get($name)
    {
        list($keyid, $secret) = [sysconf('storage_oss_keyid'), sysconf('storage_oss_secret')];
        $ossClient = new OssClient($keyid, $secret, $this->upload(), true);
        return $ossClient->getObject(sysconf('storage_oss_bucket'), $name);
    }

    /**
     * 获取文件当前URL地址
     * @param string $name 文件HASH名称
     * @return boolean|string
     * @throws \OSS\Core\OssException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function url($name)
    {
        if ($this->has($name) === false) return false;
        return $this->base($name);
    }

    /**
     * 获取AliOSS上传地址
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function upload()
    {
        $domain = sysconf('storage_oss_domain');
        $protocol = request()->isSsl() ? 'https' : 'http';
        return "{$protocol}://{$domain}";
    }

    /**
     * 获取阿里云对象存储URL前缀
     * @param string $name
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function base($name = '')
    {
        $domain = sysconf('storage_oss_domain');
        switch (strtolower(sysconf('storage_oss_is_https'))) {
            case 'https':
                return "https://{$domain}/{$name}";
            case 'http':
                return "http://{$domain}/{$name}";
            case 'auto':
                return "//{$domain}/{$name}";
        }
        throw new \think\Exception('未设置阿里云文件地址协议');
    }

    /**
     * 阿里云OSS
     * @param string $name
     * @param string $content
     * @return array|null
     */
    public function save($name, $content)
    {
        try {
            $endpoint = 'http://' . sysconf('storage_oss_domain');
            $ossClient = new OssClient(sysconf('storage_oss_keyid'), sysconf('storage_oss_secret'), $endpoint, true);
            $result = $ossClient->putObject(sysconf('storage_oss_bucket'), $name, $content);
            $baseUrl = explode('://', $result['oss-request-url'])[1];
            if (strtolower(sysconf('storage_oss_is_https')) === 'http') $site_url = "http://{$baseUrl}";
            elseif (strtolower(sysconf('storage_oss_is_https')) === 'https') $site_url = "https://{$baseUrl}";
            else $site_url = "//{$baseUrl}";
            return ['file' => $name, 'hash' => $result['content-md5'], 'key' => $name, 'url' => $site_url];
        } catch (\Exception $err) {
            Log::error('阿里云OSS文件上传失败, ' . $err->getMessage());
        }
        return null;
    }

}