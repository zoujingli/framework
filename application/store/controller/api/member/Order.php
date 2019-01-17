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

namespace app\store\controller\api\member;

use app\store\controller\api\Member;
use think\Db;

/**
 * 订单接口管理
 * Class Order
 * @package app\store\controller\api\member
 */
class Order extends Member
{

    /**
     * 创建订单
     */
    public function set()
    {

    }

    /**
     * 获取订单列表
     */
    public function gets()
    {

    }

    /**
     * 获取订单信息
     */
    public function get()
    {

    }

    /**
     * 订单取消
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function cancel()
    {
        $where = [
            'mid'      => $this->member['id'],
            'order_no' => $this->request->post('order_no'),
        ];
        $order = Db::name('StoreOrder')->where($where)->find();
        if (empty($order)) $this->error('待取消的订单不存在，请稍候再试！');
        if (in_array($order['status'], ['1', '2'])) {
            $result = Db::name('StoreOrder')->where($where)->update([
                'status'       => '0',
                'cancel_state' => '1',
                'cancel_at'    => date('Y-m-d H:i:s'),
                'cancel_desc'  => '用户主动取消订单！',
            ]);
            if ($result !== false) $this->success('订单取消成功！');
            $this->error('订单取消失败，请稍候再试！');
        }
        $this->error('该订单状态不允许取消哦~');
    }

    /**
     * 订单确认收货
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function confirm()
    {
        $where = [
            'mid'      => $this->member['id'],
            'order_no' => $this->request->post('order_no'),
        ];
        $order = Db::name('StoreOrder')->where($where)->find();
        if (empty($order)) $this->error('待确认的订单不存在，请稍候再试！');
        if (in_array($order['status'], ['4'])) {
            $result = Db::name('StoreOrder')->where($where)->update(['status' => '5']);
            if ($result !== false) $this->success('订单确认成功！');
            $this->error('订单取确认失败，请稍候再试！');
        }
        $this->error('该订单状态不允许确认哦~');
    }
}