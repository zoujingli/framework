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
use OSS\Model\CorsConfig;
use OSS\Model\CorsRule;
use OSS\OssClient;

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
        $protocol = request()->isSsl() ? 'https' : 'http';
        return "{$protocol}://" . sysconf('storage_oss_domain');
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
            return ['file' => $name, 'hash' => $result['content-md5'], 'key' => $name, 'url' => $this->base($name)];
        } catch (\Exception $err) {
            \think\facade\Log::error('阿里云OSS文件上传失败, ' . $err->getMessage());
            return null;
        }
    }

    /**
     * 创建OSS空间名称
     * @param string $bucket OSS空间名称
     * @return string 返回新创建的域名
     * @throws \OSS\Core\OssException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function setBucket($bucket)
    {
        $acl = OssClient::OSS_ACL_TYPE_PUBLIC_READ_WRITE;
        $endpoint = 'http://' . sysconf('storage_oss_endpoint');
        $client = new OssClient(sysconf('storage_oss_keyid'), sysconf('storage_oss_secret'), $endpoint);
        // 空间及权限处理
        if ($client->doesBucketExist($bucket)) {
            $result = $client->getBucketMeta($bucket);
            if ($client->getBucketAcl($bucket) !== $acl) {
                $client->putBucketAcl($bucket, $acl);
            }
        } else {
            $result = $client->createBucket($bucket, $acl);
        }
        // CORS 跨域处理
        $corsRule = new CorsRule();
        $corsRule->addAllowedHeader('*');
        $corsRule->addAllowedOrigin('*');
        $corsRule->addAllowedMethod('GET');
        $corsRule->addAllowedMethod('POST');
        $corsRule->setMaxAgeSeconds(36000);
        $corsConfig = new CorsConfig();
        $corsConfig->addRule($corsRule);
        $client->putBucketCors($bucket, $corsConfig);
        $domain = pathinfo($result['oss-request-url'], 2);
        sysconf('storage_oss_bucket', $bucket);
        sysconf('storage_oss_domain', $domain);
        return $domain;
    }

    /**
     * 获取空间列表
     * @return array
     * @throws \OSS\Core\OssException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function getBucketList()
    {
        list($endpoint, $data) = ['http://' . sysconf('storage_oss_endpoint'), []];
        $client = new OssClient(sysconf('storage_oss_keyid'), sysconf('storage_oss_secret'), $endpoint);
        foreach ($client->listBuckets()->getBucketList() as $bucket) array_push($data, [
            'bucket'    => $bucket->getName(), 'location' => $bucket->getLocation(),
            'create_at' => date('Y-m-d H:i:s', strtotime($bucket->getCreateDate())),
        ]);
        return $data;
    }

    /**
     * 获取文件路径
     * @param string $name
     * @return string
     */
    public function path($name)
    {
        return $name;
    }

    /**
     * 获取文件信息
     * @param string $name
     * @return array|null
     * @throws \OSS\Core\OssException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function info($name)
    {
        if ($this->has($name)) {
            $endpoint = 'http://' . sysconf('storage_oss_endpoint');
            $client = new OssClient(sysconf('storage_oss_keyid'), sysconf('storage_oss_secret'), $endpoint);
            $result = $client->getObjectMeta(sysconf('storage_oss_bucket'), $name);
            return ['file' => $name, 'hash' => $result['content-md5'], 'key' => $name, 'url' => $this->base($name)];
        }
        return null;
    }

}