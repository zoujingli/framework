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
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use think\facade\Log;


/**
 * 七牛云文件驱动
 * Class Qiniu
 * @package app\admin\logic\driver
 */
class Qiniu extends File
{

    /**
     * 检查文件是否已经存在
     * @param string $name 文件名称
     * @return boolean
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function has($name)
    {
        $auth = new Auth(sysconf('storage_qiniu_access_key'), sysconf('storage_qiniu_secret_key'));
        $bucketMgr = new BucketManager($auth);
        list($ret, $err) = $bucketMgr->stat(sysconf('storage_qiniu_bucket'), $name);
        return $err === null;
    }

    /**
     * 根据Key读取文件内容
     * @param string $name
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function get($name)
    {
        $auth = new Auth(sysconf('storage_qiniu_access_key'), sysconf('storage_qiniu_secret_key'));
        return file_get_contents($auth->privateDownloadUrl($this->base() . $name));
    }

    /**
     * 获取文件当前URL地址
     * @param string $name
     * @return boolean|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function url($name)
    {
        if ($this->has($name) === false) {
            return false;
        }
        return self::base($name);
    }

    /**
     * 根据配置获取到七牛云文件上传目标地址
     * @param boolean $client
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function upload($client = true)
    {
        $region = sysconf('storage_qiniu_region');
        $isHttps = !!sysconf('storage_qiniu_is_https');
        switch ($region) {
            case '华东':
                if ($isHttps) {
                    return $client ? 'https://upload.qiniup.com' : 'https://upload.qiniup.com';
                }
                return $client ? 'http://upload.qiniup.com' : 'http://upload.qiniup.com';
            case '华北':
                if ($isHttps) {
                    return $client ? 'https://upload-z1.qiniup.com' : 'https://up-z1.qiniup.com';
                }
                return $client ? 'http://upload-z1.qiniup.com' : 'http://up-z1.qiniup.com';
            case '北美':
                if ($isHttps) {
                    return $client ? 'https://upload-na0.qiniup.com' : 'https://up-na0.qiniup.com';
                }
                return $client ? 'http://upload-na0.qiniup.com' : 'http://up-na0.qiniup.com';
            case '华南':
                if ($isHttps) {
                    return $client ? 'https://upload-z2.qiniup.com' : 'https://up-z2.qiniup.com';
                }
                return $client ? 'http://upload-z2.qiniup.com' : 'http://up-z2.qiniup.com';
        }
        throw new \think\Exception('未配置七牛云存储区域');
    }

    /**
     * 获取七牛云URL前缀
     * @param string $name
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function base($name = '')
    {
        $domain = sysconf('storage_qiniu_domain');
        switch (strtolower(sysconf('storage_qiniu_is_https'))) {
            case 'https':
                return "https://{$domain}/{$name}";
            case 'http':
                return "http://{$domain}/{$name}";
            case 'auto':
                return "//{$domain}/{$name}";
        }
        throw new \think\Exception('未设置七牛云文件地址协议');
    }

    /**
     * 七牛云存储
     * @param string $name
     * @param string $content
     * @return array|null
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function save($name, $content)
    {
        $auth = new Auth(sysconf('storage_qiniu_access_key'), sysconf('storage_qiniu_secret_key'));
        $token = $auth->uploadToken(sysconf('storage_qiniu_bucket'));
        $uploadMgr = new UploadManager();
        list($result, $err) = $uploadMgr->put($token, $name, $content);
        if ($err !== null) {
            Log::error('七牛云文件上传失败');
            return null;
        }
        $result['file'] = $name;
        $result['url'] = $this->base() . $name;
        return $result;
    }

}