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

namespace app\wechat\logic;

use think\Db;

/**
 * 微信粉丝信息
 * Class Fans
 * @package app\wechat\logic
 */
class Fans
{

    /**
     * 增加或更新粉丝信息
     * @param array $user 粉丝信息
     * @param string $appid 公众号APPID
     * @return boolean
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function set(array $user, $appid = '')
    {
        if (!empty($user['subscribe_time'])) {
            $user['subscribe_at'] = date('Y-m-d H:i:s', $user['subscribe_time']);
        }
        if (isset($user['tagid_list']) && is_array($user['tagid_list'])) {
            $user['tagid_list'] = is_array($user['tagid_list']) ? join(',', $user['tagid_list']) : '';
        }
        foreach (['country', 'province', 'city', 'nickname', 'remark'] as $k) {
            isset($user[$k]) && $user[$k] = emoji_encode($user[$k]);
        }
        if ($appid !== '') $user['appid'] = $appid;
        unset($user['privilege'], $user['groupid']);
        return data_save('WechatFans', $user, 'openid');
    }

    /**
     * 获取粉丝信息
     * @param string $openid
     * @return array|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function get($openid)
    {
        $user = Db::name('WechatFans')->where(['openid' => $openid])->find();
        foreach (['country', 'province', 'city', 'nickname', 'remark'] as $k) {
            isset($user[$k]) && $user[$k] = emoji_decode($user[$k]);
        }
        return $user;
    }

    /**
     * 同步粉丝列表成功
     * @return boolean
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function sync()
    {
        $appid = Wechat::getAppid();
        $wechat = \We::WeChatUser(Wechat::config());
        $next = ''; // 获取远程粉丝
        while (true) if (is_array($result = $wechat->getUserList($next)) && !empty($result['data']['openid'])) {
            foreach (array_chunk($result['data']['openid'], 100) as $chunk) {
                if (is_array($list = $wechat->getBatchUserInfo($chunk)) && !empty($list['user_info_list'])) {
                    foreach ($list['user_info_list'] as $user) self::set($user, $appid);
                }
            }
            if (in_array($result['next_openid'], $result['data']['openid'])) break;
            else $next = $result['next_openid'];
        }
        $next = ''; // 同步粉丝黑名单
        while (true) if (is_array($result = $wechat->getBlackList($next)) && !empty($result['data']['openid'])) {
            foreach (array_chunk($result['data']['openid'], 100) as $chunk) {
                $where = [['is_black', 'eq', '0'], ['openid', 'in', $chunk]];
                Db::name('WechatFans')->failException(true)->where($where)->update(['is_black' => '1']);
            }
            if (in_array($result['next_openid'], $result['data']['openid'])) break;
            else $next = $result['next_openid'];
        }
        // 同步粉丝标签列表
        $wechat = \We::WeChatTags(Wechat::config());
        if (is_array($list = $wechat->getTags()) && !empty($list['tags'])) {
            foreach ($list['tags'] as &$tag) $tag['appid'] = $appid;
            Db::name('WechatFans')->whereRaw('1=1')->delete();
            Db::name('WechatFansTags')->insertAll($list['tags']);
        }
        return true;
    }

}