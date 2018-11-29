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

namespace app\wechat;

use app\admin\logic\Queue;
use app\wechat\logic\Fans;
use app\wechat\logic\Wechat;
use think\Db;

/**
 * Class Jobs
 * @package app\wechat
 */
class Jobs extends Queue
{
    
    /**
     * 当前任务URI
     */
    const URI = 'app\\wechat\\Jobs';

    /**
     * 执行任务
     * @return boolean
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function execute()
    {
        $appid = Wechat::getAppid();
        $wechat = \We::WeChatUser(Wechat::config());
        $next = ''; // 获取远程粉丝
        $this->output->writeln('准备同步粉丝列表...');
        while (true) if (is_array($result = $wechat->getUserList($next)) && !empty($result['data']['openid'])) {
            foreach (array_chunk($result['data']['openid'], 100) as $chunk) {
                if (is_array($list = $wechat->getBatchUserInfo($chunk)) && !empty($list['user_info_list'])) {
                    foreach ($list['user_info_list'] as $user) Fans::set($user, $appid);
                }
            }
            if (in_array($result['next_openid'], $result['data']['openid'])) break;
            else $next = $result['next_openid'];
        }
        $next = ''; // 同步粉丝黑名单
        $this->output->writeln('准备同步粉丝黑名单列表...');
        while (true) if (is_array($result = $wechat->getBlackList($next)) && !empty($result['data']['openid'])) {
            foreach (array_chunk($result['data']['openid'], 100) as $chunk) {
                $where = [['is_black', 'eq', '0'], ['openid', 'in', $chunk]];
                Db::name('WechatFans')->failException(true)->where($where)->update(['is_black' => '1']);
            }
            if (in_array($result['next_openid'], $result['data']['openid'])) break;
            else $next = $result['next_openid'];
        }
        // 同步粉丝标签列表
        $this->output->writeln('准备同步粉丝标签列表...');
        $wechat = \We::WeChatTags(Wechat::config());
        if (is_array($list = $wechat->getTags()) && !empty($list['tags'])) {
            foreach ($list['tags'] as &$tag) $tag['appid'] = $appid;
            Db::name('WechatFansTags')->where('1=1')->delete();
            Db::name('WechatFansTags')->insertAll($list['tags']);
        }
        return true;
    }

}