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
     * 获取订单实际支付金额
     * @param array $member
     * @param array $orderSpec
     * @param integer $number
     * @return array|mixed
     */
    public static function buildOrderPrice($member = [], $orderSpec = [], $number = 1)
    {
        switch (intval($member['vip_level'])) {
            case 0: # 游客
                $orderSpec['price_real'] = $orderSpec['price_selling'] * $number;
                return $orderSpec;
            case 1: # 临时会员
                switch (intval($orderSpec['vip_mod'])) {
                    case 0: # 没有权限
                    case 1: # 临时会员，都按会员价格计算
                        $orderSpec['price_real'] = $orderSpec['price_member'] * $number;
                        if ($orderSpec['price_real'] > 0) { # 会员不免费时快递费与服务费都免
                            $orderSpec['price_express'] = 0;
                            $orderSpec['price_service'] = 0;
                        }
                        return $orderSpec;
                    case 2: # 临时会员升VIP1，按销售价格计算
                        $orderSpec['price_real'] = $orderSpec['price_selling'] * $number;
                        return $orderSpec;
                }
                return $orderSpec['price_member'];
            case 2: # VIP1会员
                switch (intval($orderSpec['vip_mod'])) {
                    case 0: # 没有权限
                    case 1: # 临时会员
                        $orderSpec['price_real'] = $orderSpec['price_member'] * $number;
                        return $orderSpec;
                    case 2: # 普通会员
                        $diffPrice = $orderSpec['price_selling'] - $orderSpec['vip_discount'];
                        $orderSpec['price_real'] = ($diffPrice < 0 ? 0 : $diffPrice) * $number;
                        $orderSpec['discount_price'] = $orderSpec['vip_discount'];
                        $orderSpec['discount_desc'] = 'VIP1升VIP2优惠金额';
                        return $orderSpec;
                }
                return $orderSpec;
            case 3: # VIP2会员
                switch (intval($orderSpec['vip_mod'])) {
                    case 0: # 没有权限
                    case 1: # 临时会员
                        $orderSpec['price_real'] = $orderSpec['price_member'] * $number;
                        return $orderSpec;
                    case 2: # 普通会员
                        $diffPrice = $orderSpec['price_selling'] - $orderSpec['vip_discount'];
                        $orderSpec['price_real'] = ($diffPrice < 0 ? 0 : $diffPrice) * $number;
                        $orderSpec['discount_price'] = $orderSpec['vip_discount'];
                        $orderSpec['discount_desc'] = 'VIP1升VIP2优惠金额';
                        return $orderSpec;
                }

        }

        /**
         * 更新会员级别信息
         * @param string $order_no
         * @throws \think\db\exception\DataNotFoundException
         * @throws \think\db\exception\ModelNotFoundException
         * @throws \think\exception\DbException
         */
        public
        static function updateMember($order_no)
        {
            $where = ['order_no' => $order_no, 'pay_state' => '1'];
            $order = Db::name('StoreOrder')->where($where)->find();

        }
    }