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

namespace app\wechat\command;

use app\wechat\service\Wechat;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

/**
 * 微信粉丝管理
 * Class Fans
 * @package app\wechat\command
 */
class Fans extends Command
{

    /**
     * 需要处理的模块
     * @var array
     */
    protected $module = ['list', 'black', 'tags'];

    /**
     * 执行指令
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    protected function execute(Input $input, Output $output)
    {
        $output->writeln('preparing for synchronization of fan command...');
        foreach ($this->module as $m) {
            if (method_exists($this, $fun = "_{$m}")) $this->$fun();
        }
        $output->writeln('synchronized fans command completion.');
    }


    /**
     * 同步微信粉丝列表
     * @param string $next
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    protected function _list($next = '')
    {
        $appid = Wechat::getAppid();
        $wechat = Wechat::WeChatUser();
        $this->output->warning('preparing a synchronized fan list...');
        while (true) if (is_array($result = $wechat->getUserList($next)) && !empty($result['data']['openid'])) {
            foreach (array_chunk($result['data']['openid'], 100) as $chunk) {
                if (is_array($list = $wechat->getBatchUserInfo($chunk)) && !empty($list['user_info_list'])) {
                    foreach ($list['user_info_list'] as $user) \app\wechat\service\Fans::set($user, $appid);
                }
            }
            if (in_array($result['next_openid'], $result['data']['openid'])) break;
            else $next = $result['next_openid'];
        }
        $this->output->writeln('synchronized fan list completion.');
    }

    /**
     * 同步粉丝黑名单列表
     * @param string $next
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function _black($next = '')
    {
        $wechat = Wechat::WeChatUser();
        $this->output->writeln('prepare a synchronized fan blacklist...');
        while (true) if (is_array($result = $wechat->getBlackList($next)) && !empty($result['data']['openid'])) {
            foreach (array_chunk($result['data']['openid'], 100) as $chunk) {
                $where = [['is_black', 'eq', '0'], ['openid', 'in', $chunk]];
                Db::name('WechatFans')->failException(true)->where($where)->update(['is_black' => '1']);
            }
            if (in_array($result['next_openid'], $result['data']['openid'])) break;
            else $next = $result['next_openid'];
        }
        $this->output->writeln('synchronized fan blacklist completion.');
    }

    /**
     * 同步粉丝标签列表
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function _tags()
    {
        $appid = Wechat::getAppid();
        $wechat = Wechat::WeChatTags();
        $this->output->writeln('prepare a list of synchronized fan tags...');
        if (is_array($list = $wechat->getTags()) && !empty($list['tags'])) {
            foreach ($list['tags'] as &$tag) $tag['appid'] = $appid;
            Db::name('WechatFansTags')->where('1=1')->delete();
            Db::name('WechatFansTags')->insertAll($list['tags']);
        }
        $this->output->writeln('synchronized fan tag list successful.');
    }

}