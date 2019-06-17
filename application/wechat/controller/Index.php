<?php


namespace app\wechat\controller;

use library\Controller;
use think\Db;

/**
 * 微信数据统计
 * Class Index
 * @package app\wechat\controller
 */
class Index extends Controller
{
    /**
     * 微信数据统计
     */
    public function index()
    {
        $this->totalJson = ['xs' => [], 'ys' => []];
        for ($i = 5; $i >= 0; $i--) {
            $time = strtotime("-{$i} months");
            $where = [['subscribe_at', '<', date('Y-m-32 00:00:00', $time)]];
            $this->totalJson['xs'][] = date('Y年m月', $time);
            $item = ['_0' => 0, '_1' => 0];
            $list = Db::name('WechatFans')->field('count(1) count,is_black black')->where($where)->group('is_black')->select();
            foreach ($list as $vo) $item["_{$vo['black']}"] = $vo['count'];
            $this->totalJson['ys']['_0'][] = $item['_0'];
            $this->totalJson['ys']['_1'][] = $item['_1'];
        }
        $this->totalFans = Db::name('WechatFans')->where(['is_black' => '0'])->count();
        $this->totalBlack = Db::name('WechatFans')->where(['is_black' => '1'])->count();
        $this->totalNews = Db::name('WechatNews')->where(['is_deleted' => '0'])->count();
        $this->totalRule = Db::name('WechatKeys')->count();
        $this->fetch();
    }

}