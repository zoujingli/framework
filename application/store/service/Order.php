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

namespace app\store\service;

use think\Db;

/**
 * 订单服务管理器
 * Class Order
 * @package app\store\service
 */
class Order
{
    /**
     * 根据订单号升级会员等级
     * @param integer $mid 会员ID
     * @param string $order_no 订单单号
     * @return boolean
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function update($mid, $order_no)
    {
        $member = Db::name('StoreMember')->where(['id' => $mid])->find();
        if (empty($member)) return false;
        $order = Db::name('StoreOrder')->where(['order_no' => $order_no, 'pay_state' => '1'])->find();
        if (empty($order)) return false;
        $vipMods = Db::name('StoreOrderList')->where(['order_no' => $order_no])->whereIn('vip_mod', ['1', '2'])->column('vip_mod');
        if (empty($vipMods)) return false;
        $vipMod = max($vipMods);
        $isUsedOrder = Db::name('StoreMemberHistory')->where(['order_no' => $order_no])->count() > 0;
        if ($isUsedOrder) return false;

    }
}