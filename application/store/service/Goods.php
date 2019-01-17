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
 * 商品数据管理
 * Class Goods
 * @package app\store\logic
 */
class Goods
{
    /**
     * 同步商品库存信息
     * @param integer $goodsId
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public static function syncStock($goodsId)
    {
        // 入库统计更新
        $fields = "goods_id,goods_spec,concat(goods_id,'::',goods_spec) spec,sum(number_stock) stock";
        $stockList = Db::name('StoreGoodsStock')->field($fields)->where(['goods_id' => $goodsId])->group('spec')->select();
        foreach ($stockList as $vo) Db::name('StoreGoodsList')->where([
            'goods_id' => $goodsId, 'goods_spec' => $vo['goods_spec'],
        ])->update(['number_stock' => $vo['stock']]);
        // 销售统计更新
        $smap = [['goods_id', 'eq', $goodsId], ['status', 'in', ['0', '1']]];
        Db::name('StoreGoods')->where(['id' => $goodsId])->update([
            'number_stock' => (int)array_sum(array_column($stockList, 'stock')),
            'number_sales' => 0//(int)Db::name('StoreOrderList')->where($smap)->sum('goods_number'),
        ]);
    }

}