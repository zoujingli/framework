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
        // 检查订单是否已经使用
        $usedOrderCount = Db::name('StoreMemberHistory')->where(['order_no' => $order_no])->count() > 0;
        if ($usedOrderCount) return false;
        // 订单支付验证
        $order = Db::name('StoreOrder')->where(['order_no' => $order_no, 'pay_state' => '1'])->find();
        if (empty($order)) return false;
        // 获取订单升级参数
        $where = [['order_no', 'eq', $order_no], ['vip_mod', 'in', ['1', '2']]];
        $goods = Db::name('StoreOrderList')->where($where)->order('vip_mod desc')->find();
        if (empty($goods)) return false;
        try {
            $toTime = time();
            if (!empty($member['vip_date']) && $member['vip_date'] > date('Y-m-d')) $toTime = strtotime("{$member['vip_date']} 12:00:00");
            $history = ['mid' => $mid, 'order_no' => $order_no, 'from_level' => $member['vip_level'], 'from_date' => $member['vip_date']];
            Db::transaction(function () use ($mid, $history, $member, $goods, $toTime) {
                // 游客会员
                if (intval($member['vip_level']) === 0 && in_array(intval($goods['vip_mod']), [1, 2])) {
                    if (intval($goods['vip_mod']) === 1) { # 购买临时礼包，升级临时会员
                        $history['to_level'] = '1';
                        $history['to_date'] = date('Y-m-d', strtotime("+{$goods['vip_month']} month", $toTime));
                        $history['desc'] = '游客会员购买临时礼包升级临时会员';
                    }
                    if (intval($goods['vip_mod']) === 2) { # 购买普通礼包，升级到VIP1
                        $history['to_level'] = '2';
                        $history['to_date'] = date('Y-m-d', strtotime("+{$goods['vip_month']} month", $toTime));
                        $history['desc'] = '游客会员购买临时礼包直接续期';
                    }
                    Db::name('StoreMemberHistory')->insert($history);
                    Db::name('StoreMember')->where(['id' => $mid])->update(['vip_level' => $history['to_level'], 'vip_date' => $history['to_date']]);
                }
                // 临时会员
                if (intval($member['vip_level']) === 1 && in_array(intval($goods['vip_mod']), [1, 2])) {
                    if (intval($goods['vip_mod']) === 1) { # 购买临时礼包直接续期会员等级
                        $history['to_level'] = '1';
                        $history['to_date'] = date('Y-m-d', strtotime("+{$goods['vip_month']} month", $toTime));
                        $history['desc'] = '临时会员购买普通礼包直接续期';
                    }
                    if (intval($goods['vip_mod']) === 2) { # 购买普通礼包升级到VIP1
                        $history['to_level'] = '2';
                        $history['to_date'] = date('Y-m-d', strtotime("+{$goods['vip_month']} month", $toTime));
                        $history['desc'] = '临时会员购买普通礼包升级VIP1';
                    }
                    Db::name('StoreMemberHistory')->insert($history);
                    Db::name('StoreMember')->where(['id' => $mid])->update(['vip_level' => $history['to_level'], 'vip_date' => $history['to_date']]);
                    return true;
                }
                // VIP1会员，购买会员礼包升级到VIP2
                if (intval($member['vip_level']) === 2 && intval($goods['vip_mod']) === 2) {
                    $history['to_level'] = '3';
                    $history['to_date'] = $member['vip_date'];
                    $history['desc'] = 'VIP1购买普通礼包升级VIP2';
                    Db::name('StoreMemberHistory')->insert($history);
                    Db::name('StoreMember')->where(['id' => $mid])->update(['vip_level' => $history['to_level'], 'vip_date' => $history['to_date']]);
                    return true;
                }
                // VIP2会员, 购买会员礼包直接续期会员等级
                if (intval($member['vip_level']) === 3 && intval($goods['vip_mod']) === 2) {
                    $history['to_level'] = '3';
                    $history['to_date'] = date('Y-m-d', strtotime("+{$goods['vip_month']} month", $toTime));
                    $history['desc'] = 'VIP2购买普通礼包直接VIP2续期';
                    Db::name('StoreMemberHistory')->insert($history);
                    Db::name('StoreMember')->where(['id' => $mid])->update(['vip_level' => $history['to_level'], 'vip_date' => $history['to_date']]);
                    return true;
                }
            });
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}