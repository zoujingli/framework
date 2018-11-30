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

namespace app\wechat\controller;

use app\admin\logic\Queue;
use app\wechat\Jobs;
use app\wechat\logic\Wechat;
use library\Controller;
use think\Db;
use think\exception\HttpResponseException;

/**
 * 微信粉丝管理
 * Class Fans
 * @package app\wechat\controller
 */
class Fans extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'WechatFans';

    /**
     * 微信粉丝管理
     * @return array
     */
    public function index()
    {
        $this->title = '微信粉丝管理';
        $query = $this->_query($this->table)->like('nickname')->equal('subscribe,is_black');
        return $this->_page($query->dateBetween('subscribe_at')->db()->order('subscribe_time desc'));
    }

    /**
     * 微信粉丝列表处理
     * @param array $data
     */
    protected function _index_page_filter(array &$data)
    {
        foreach ($data as &$user) foreach (['country', 'province', 'city', 'nickname', 'remark'] as $k) {
            if (isset($user[$k])) $user[$k] = emoji_decode($user[$k]);
        }
    }

    /**
     * 批量拉黑粉丝
     */
    public function setBlack()
    {
        try {
            foreach (array_chunk(explode(',', $this->request->post('openid')), 20) as $openids) {
                \We::WeChatUser(Wechat::config())->batchBlackList($openids);
                Db::name('WechatFans')->whereIn('openid', $openids)->update(['is_black' => '1']);
            }
            $this->success('拉黑粉丝信息成功！');
        } catch (HttpResponseException $exception) {
            throw  $exception;
        } catch (\Exception $e) {
            $this->error("拉黑粉丝信息失败，请稍候再试！{$e->getMessage()}");
        }
    }

    /**
     *批量取消拉黑粉丝
     */
    public function delBlack()
    {
        try {
            foreach (array_chunk(explode(',', $this->request->post('openid')), 20) as $openids) {
                \We::WeChatUser(Wechat::config())->batchUnblackList($openids);
                Db::name('WechatFans')->whereIn('openid', $openids)->update(['is_black' => '0']);
            }
            $this->success('取消拉黑粉丝信息成功！');
        } catch (HttpResponseException $exception) {
            throw  $exception;
        } catch (\Exception $e) {
            $this->error("取消拉黑粉丝信息失败，请稍候再试！{$e->getMessage()}");
        }
    }

    /**
     * 同步粉丝列表
     */
    public function sync()
    {
        try {
            Queue::add('同步粉丝列表', Jobs::URI, 0, [], 0);
            $this->success('创建同步粉丝任务成功，需要时间来完成。<br>请到系统任务管理查看进度！');
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $e) {
            $this->error("创建同步粉丝任务失败，请稍候再试！<br> {$e->getMessage()}");
        }
    }

}