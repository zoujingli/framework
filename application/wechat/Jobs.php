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
     */
    public function execute()
    {
        try {
            $appid = Wechat::getAppid();
            $wechat = \We::WeChatUser(Wechat::config());
            $next = ''; // 获取远程粉丝
            $this->writeln('准备同步粉丝列表...');
            while (is_array($result = $wechat->getUserList($next)) && !empty($result['data']['openid'])) {
                foreach (array_chunk($result['data']['openid'], 100) as $chunk)
                    if (is_array($list = $wechat->getBatchUserInfo($chunk)) && !empty($list['user_info_list']))
                        foreach ($list['user_info_list'] as $user) Fans::set($user, $appid);
                $next = $result['next_openid'];
                if (in_array($result['next_openid'], $result['data']['openid'])) break;
            }
            $next = ''; // 同步粉丝黑名单
            $this->writeln('准备同步粉丝黑名单列表...');
            while (is_array($result = $wechat->getBlackList($next)) && !empty($result['data']['openid'])) {
                foreach (array_chunk($result['data']['openid'], 100) as $chunk) {
                    $where = [['is_black', 'eq', '0'], ['openid', 'in', $chunk]];
                    Db::name('WechatFans')->where($where)->update(['is_black' => '1']);
                }
                $next = $result['next_openid'];
                if (in_array($result['next_openid'], $result['data']['openid'])) break;
            }
            // 同步粉丝标签列表
            $this->writeln('准备同步粉丝标签列表...');
            if (is_array($list = \We::WeChatTags(Wechat::config())->getTags()) && !empty($list['tags'])) {
                foreach ($list['tags'] as &$tag) $tag['appid'] = $appid;
                Db::name('WechatFansTags')->where('1=1')->delete();
                Db::name('WechatFansTags')->insertAll($list['tags']);
            }
            return true;
        } catch (\Exception $e) {
            $this->statusDesc = $e->getMessage();
            return false;
        }
    }

}