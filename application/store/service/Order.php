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
     * @param array $order
     */
    public static function buildOrderPrice($member = [], $order = [])
    {

    }

    /**
     * 更新会员级别信息
     * @param string $order_no
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function updateMember($order_no)
    {
        $where = ['order_no' => $order_no, 'pay_state' => '1'];
        $order = Db::name('StoreOrder')->where($where)->find();

    }
}