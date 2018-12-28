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

namespace app\service\controller;

use library\Controller;

/**
 * Class Index
 * @package app\service\controller
 */
class Index extends Controller
{

    /**
     * 定义当前操作表名
     * @var string
     */
    public $table = 'WechatServiceConfig';

    /**
     * 微信基础参数配置
     * @return string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $this->title = '微信授权管理';
        return $this->_query($this->table)
            ->like('authorizer_appid,nick_name,principal_name')
            ->equal('service_type')->dateBetween('create_at')
            ->order('id desc')->page();
    }

    /**
     * 同步获取权限
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function sync()
    {
        $appid = $this->request->get('appid');
        $where = ['authorizer_appid' => $appid, 'is_deleted' => '0', 'status' => '1'];
        $author = Db::name('WechatServiceConfig')->where($where)->find();
        empty($author) && $this->error('无效的授权信息，请同步其它公众号！');
        $wechat = WechatService::service();
        $info = BuildService::filter($wechat->getAuthorizerInfo($appid));
        $info['authorizer_appid'] = $appid;
        if (DataService::save('WechatServiceConfig', $info, 'authorizer_appid')) {
            $this->success('更新授权信息成功！', '');
        }
        $this->error('获取授权信息失败，请稍候再试！');
    }

    /**
     * 同步获取所有授权公众号记录
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function syncall()
    {
        $wechat = WechatService::service();
        $result = $wechat->getAuthorizerList();
        foreach ($result['list'] as $item) {
            if (!empty($item['refresh_token']) && !empty($item['auth_time'])) {
                $data = BuildService::filter($wechat->getAuthorizerInfo($item['authorizer_appid']));
                $data['authorizer_appid'] = $item['authorizer_appid'];
                $data['authorizer_refresh_token'] = $item['refresh_token'];
                $data['create_at'] = date('Y-m-d H:i:s', $item['auth_time']);
                if (!DataService::save('WechatServiceConfig', $data, 'authorizer_appid')) {
                    $this->error('获取授权信息失败，请稍候再试！', '');
                }
            }
        }
        $this->success('同步所有授权信息成功！', '');
    }

    /**
     * 删除微信
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function del()
    {
        $wechat = WechatService::service();
        if (DataService::update($this->table)) {
            $this->success("微信删除成功！", '');
        }
        $this->error("微信删除失败，请稍候再试！");
    }

    /**
     * 微信禁用
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function forbid()
    {
        if (DataService::update($this->table)) {
            $this->success("微信禁用成功！", '');
        }
        $this->error("微信禁用失败，请稍候再试！");
    }

    /**
     * 微信禁用
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function resume()
    {
        if (DataService::update($this->table)) {
            $this->success("微信启用成功！", '');
        }
        $this->error("微信启用失败，请稍候再试！");
    }
}