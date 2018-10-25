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

namespace app\admin\controller;

use app\admin\logic\File;
use library\Controller;

/**
 * 系统配置
 * Class Config
 * @package app\admin\controller
 */
class Config extends Controller
{
    /**
     * 默认数据模型
     * @var string
     */
    protected $table = 'SystemConfig';

    /**
     * 系统参数配置
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function info()
    {
        $this->title = '系统参数配置';
        if ($this->request->isGet()) return $this->fetch();
        foreach ($this->request->post() as $k => $v) sysconf($k, $v);
        $this->success('系统参数配置保存成功！');

    }

    /**
     * 文件存储配置
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function file()
    {
        if ($this->request->isGet()) {
            $this->title = '文件存储配置';
            $this->ossEndpoints = [
                '青岛节点'    => 'oss-cn-qingdao.aliyuncs.com',
                '北京节点'    => 'oss-cn-beijing.aliyuncs.com',
                '杭州节点'    => 'oss-cn-hangzhou.aliyuncs.com',
                '香港节点'    => 'oss-cn-hongkong.aliyuncs.com',
                '华南节点'    => 'oss-cn-shenzhen.aliyuncs.com',
                '上海节点'    => 'oss-cn-shanghai.aliyuncs.com',
                '美国硅谷节点'  => 'oss-us-west-1.aliyuncs.com',
                '亚太新加坡节点' => 'oss-ap-southeast-1.aliyuncs.com',
            ];
            return $this->fetch();
        }
        foreach ($this->request->post() as $k => $v) sysconf($k, $v);
        // 阿里云OSS动态配置
        if ($this->request->post('storage_type') === 'oss') {
            try {
                $bucket = $this->request->post('storage_oss_bucket');
                File::instance('oss')->setBucket($bucket);
            } catch (\Exception $e) {
                $this->error('阿里云OSS存储配置失效，' . $e->getMessage());
            }
            $this->success('阿里云OSS存储动态配置成功！');
        }
        $this->success('文件存储配置保存成功！');
    }

}