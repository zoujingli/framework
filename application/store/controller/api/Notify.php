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


namespace app\store\controller\api;

use think\Db;

/**
 * 通知处理
 * Class Notify
 * @package app\store\controller\api
 */
class Notify
{
    /**
     * 微信支付通知处理
     * @return string
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function wxpay()
    {
        $wechat = \app\wechat\service\Wechat::WePayOrder();
        p($notify = $wechat->getNotify());
        if ($notify['result_code'] == 'SUCCESS' && $notify['return_code'] == 'SUCCESS') {
            if ($this->update($notify['out_trade_no'], $notify['transaction_id'], $notify['cash_fee'] / 100, 'wechat')) {
                return $wechat->getNotifySuccessReply();
            }
        } else return $wechat->getNotifySuccessReply();
    }

    /**
     * 订单状态更新
     * @param string $order_no 订单号
     * @param string $pay_no 交易号
     * @param string $pay_price 交易金额
     * @param string $type 支付类型
     * @return boolean
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    private function update($order_no, $pay_no, $pay_price, $type = 'wechat')
    {
        // 检查订单支付状态
        $map = ['order_no' => $order_no, 'pay_state' => '1', 'status' => '3'];
        if (Db::name('StoreOrder')->where($map)->count() > 0) return false;
        // 更新订单支付状态
        return Db::name('StoreOrder')->where([
                'pay_state' => '0', 'order_no' => $order_no,
            ])->update([
                'pay_type'  => $type,
                'pay_no'    => $pay_no,
                'status'    => '3',
                'pay_price' => $pay_price,
                'pay_state' => '1',
                'pay_at'    => date('Y-m-d H:i:s'),
            ]) != false;
    }

}