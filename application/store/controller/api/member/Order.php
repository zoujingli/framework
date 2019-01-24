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
use library\tools\Data;
use think\Db;

/**
 * 订单接口管理
 * Class Order
 * @package app\store\controller\api\member
 */
class Order extends Member
{

    /**
     * 创建商城订单
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * 商品ID1@商品规格1@商品数量1||商品ID2@商品规格2@商品数量2
     */
    public function set()
    {
        // 订单类型
        $type = $this->request->post('type', '1'); # 1 普通订单,2 免费领取订单
        if (!in_array($type, ['1', '2'])) $this->error('订单类型错误！');
        // 商品规则
        $rule = $this->request->post('rule', '');
        if (empty($rule)) $this->error('下单商品规则不能为空！');
        // 订单处理
        list($order, $orderList) = [[], []];
        $order['type'] = $type;
        $order['mid'] = $this->mid;
        $order['order_no'] = Data::uniqidNumberCode(12);
        foreach (explode('||', $rule) as $item) {
            list($goods_id, $goods_spec, $number) = explode('@', $item);
            $goods = Db::name('StoreGoods')->where(['id' => $goods_id, 'status' => '1', 'is_deleted' => '0'])->find();
            if (empty($goods)) $this->error('查询商品主体信息失败，请稍候再试！');
            $goodsSpec = Db::name('StoreGoodsList')->where(['goods_id' => $goods_id, 'goods_spec' => $goods_spec])->find();
            if (empty($goodsSpec)) $this->error('查询商品规则信息失败，请稍候再试！');
            array_push($orderList, [
                'mid'           => $order['mid'],
                'type'          => $order['type'],
                'order_no'      => $order['order_no'],
                'goods_id'      => $goods_id,
                'goods_spec'    => $goods_spec,
                'goods_logo'    => $goods['logo'],
                'goods_title'   => $goods['title'],
                'vip_mod'       => $goods['vip_mod'],
                'vip_month'     => $goods['vip_month'],
                'vip_discount'  => $goods['vip_discount'],
                'price_market'  => $goodsSpec['price_market'],
                'price_selling' => $goodsSpec['price_selling'],
                // 服务费，普通订单不收服务费
                'price_service' => intval($type) === 1 ? '0.00' : $goods['price_service'],
                'price_express' => intval($type) === 1 ? $goods['price_express1'] : $goods['price_express2'],
                'price_real'    => intval($type) === 1 ? $goodsSpec['price_selling'] * $number : '0.00',
                'number'        => $number,
            ]);
        }
        // @todo 会员升级优惠还没有处理
        $order['status'] = '1';
        $order['price_goods'] = array_sum(array_column($orderList, 'price_real'));
        $order['price_express'] = array_sum(array_column($orderList, 'price_express'));
        $order['price_service'] = array_sum(array_column($orderList, 'price_service'));
        $order['price_total'] = $order['price_goods'] + $order['price_express'] + $order['price_service'];
        try {
            Db::name('StoreOrder')->insert($order);
            Db::name('StoreOrderList')->insertAll($orderList);
            $this->success('订单创建成功，请补全收货信息后支付！', ['order' => $order]);
        } catch (\think\exception\HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $e) {
            $this->error("创建订单失败，请稍候再试！{$e->getMessage()}");
        }
    }

    /**
     * 订单信息完成
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function perfect()
    {
        $data = $this->_input([
            'order_no'   => $this->request->post('order_no'),
            'address_id' => $this->request->post('address_id'),
        ], [
            'order_no'   => 'require',
            'address_id' => 'require',
        ], [
            'order_no.require'   => '订单号不能为空！',
            'address_id.require' => '收货地址ID不能为空（0自提可以为空）',
        ]);
        $map = ['order_no' => $data['order_no'], 'mid' => $this->member['id']];
        $order = Db::name('StoreOrder')->whereIn('status', ['1', '2'])->where($map)->find();
        if (empty($order)) $this->error('订单异常，请返回商品重新下单！');
        $update = ['status' => '2'];
        $where = ['id' => $data['address_id'], 'mid' => $this->member['id']];
        $address = Db::name('StoreMemberAddress')->where($where)->find();
        if (empty($address)) $this->error('会员收货地址异常，请刷新页面重试！');
        $update['express_address_id'] = $data['address_id'];
        $update['express_name'] = $address['name'];
        $update['express_phone'] = $address['phone'];
        $update['express_province'] = $address['province'];
        $update['express_city'] = $address['city'];
        $update['express_area'] = $address['area'];
        $update['express_address'] = $address['address'];
        if (Db::name('StoreOrder')->where($map)->update($update) !== false) {
            $params = $this->getPayParams($order['order_no'], $order['price_total']);
            $this->success('更新订单会员信息成功！', $params);
        }
        $this->error('更新订单会员信息失败，请稍候再试！');
    }

    /**
     * 获取订单支付状态
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function pay()
    {
        $order_no = $this->request->post('order_no');
        $order = Db::name('StoreOrder')->where(['order_no' => $order_no])->find();
        if (empty($order_no)) $this->error('获取订单信息异常，请稍候再试！');
        if ($order['pay_state']) $this->error('订单已经完成支付，不需要再次支付！');
        if ($order['status'] <> 3) $this->error('该订单不能发起支付哦！');
        try {
            $param = $this->getPayParams($order['order_no'], $order['price_total']);
            $this->success('获取订单支付参数成功！', $param);
        } catch (\think\exception\HttpResponseException $exception) {
            throw  $exception;
        } catch (\Exception $e) {
            $this->error("获取订单支付参数失败，{$e->getMessage()}");
        }
    }

    /**
     * 获取订单支付参数
     * @param string $order_no
     * @param string $pay_price
     * @return array
     */
    private function getPayParams($order_no, $pay_price)
    {
        $options = [
            'body'             => '商城订单支付',
            'openid'           => $this->openid,
            'out_trade_no'     => $order_no,
            'total_fee'        => '1',
            // 'total_fee'        => $pay_price * 100,
            'trade_type'       => 'JSAPI',
            'notify_url'       => url('@store/api.notify/wxpay', '', false, true),
            'spbill_create_ip' => $this->request->ip(),
        ];
        try {
            $pay = \We::WePayOrder(config('wechat.miniapp'));
            $info = $pay->create($options);
            if ($info['return_code'] === 'SUCCESS' && $info['result_code'] === 'SUCCESS') {
                return $pay->jsapiParams($info['prepay_id']);
            }
            if (isset($info['err_code_des'])) throw new \think\Exception($info['err_code_des']);
        } catch (\Exception $e) {
            $this->error("创建订单失败参数失败，{$e->getMessage()}");
        }
    }

    /**
     * 获取订单列表
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function gets()
    {
        $where = [['mid', 'eq', $this->mid]];
        if ($this->request->has('order_no', 'post', true)) {
            $where[] = ['order_no', 'eq', $this->request->post('order_no')];
        } else {
            $where[] = ['status', 'in', ['0', '2', '3', '4', '5']];
        }
        if ($this->request->has('status', 'post', true)) {
            $where[] = ['status', 'eq', $this->request->post('status')];
        }
        $result = $this->_query('StoreOrder')->where($where)->order('id desc')->page(true, false, false, 20);
        $glist = Db::name('StoreOrderList')->whereIn('order_no', array_unique(array_column($result['list'], 'order_no')))->select();
        foreach ($result['list'] as &$vo) {
            list($vo['goods_count'], $vo['list']) = [0, []];
            foreach ($glist as $goods) if ($vo['order_no'] === $goods['order_no']) {
                $vo['list'][] = $goods;
                $vo['goods_count'] += $goods['number'];
            }
        }
        $this->success('获取订单列表成功！', $result);
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