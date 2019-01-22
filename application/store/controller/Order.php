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

namespace app\store\controller;

use library\Controller;
use think\Db;

/**
 * 商城订单管理
 * Class Order
 * @package app\store\controller
 */
class Order extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'StoreOrder';

    /**
     * 商城订单列表显示
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '商城订单管理';
        $this->_query($this->table)->order('id desc')->page();
    }

    /**
     * 商城订单列表处理
     * @param array $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function _page_filter(array &$data)
    {
        $memberList = Db::name('StoreMember')->whereIn('id', array_unique(array_column($data, 'mid')))->select();
        $goodsList = Db::name('StoreOrderList')->whereIn('order_no', array_unique(array_column($data, 'order_no')))->select();
        foreach ($data as &$vo) {
            list($vo['member'], $vo['list']) = [[], []];
            foreach ($goodsList as $goods) if ($goods['order_no'] === $vo['order_no']) {
                $vo['list'][] = $goods;
            }
            foreach ($memberList as $member) if ($member['id'] === $vo['mid']) {
                $member['nickname'] = emoji_decode($member['nickname']);
                $vo['member'] = $member;
            }
        }
    }

}