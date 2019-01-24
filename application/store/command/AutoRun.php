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

namespace app\store\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

/**
 * 商城数据处理指令
 * Class AutoRun
 * @package app\store\command
 */
class AutoRun extends Command
{
    /**
     * 配置入口
     */
    protected function configure()
    {
        $this->setName('xstore:auto')->setDescription('Auto clear store order data');
    }

    /**
     * 执行指令
     * @param Input $input
     * @param Output $output
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    protected function execute(Input $input, Output $output)
    {
        # 自动取消30分钟未支付的订单
        $count = Db::name('StoreOrder')->where([
            ['pay_state', 'eq', '0'], ['status', 'in', ['1', '2']],
            ['create_at', '<', date('Y-m-d H:i:s', strtotime('-30 minutes'))],
        ])->update([
            'status'       => '0',
            'cancel_state' => '1',
            'cancel_at'    => date('Y-m-d H:i:s'),
            'cancel_desc'  => '30分钟未完成支付自动取消订单',
        ]);
        if ($count > 0) {
            $this->output->info('auto cancel order -> ' . $count);
        } else {
            $this->output->comment('not cancel order.');
        }
        # 清理一天前未支付的订单
        $list = Db::name('StoreOrder')->where([
            ['pay_state', 'eq', '0'],
            ['create_at', '<', date('Y-m-d H:i:s', strtotime('-1 day'))],
        ])->limit(20)->select();
        if (count($order_nos = array_unique(array_column($list, 'order_no'))) > 0) {
            $this->output->info('auto clear order -> ' . join(',' . PHP_EOL . "\t", $order_nos));
            Db::name('StoreOrder')->whereIn('order_no', $order_nos)->delete();
            Db::name('StoreOrderList')->whereIn('order_no', $order_nos)->delete();
        } else {
            $this->output->comment('not clear order.');
        }
    }

}