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

use library\Controller;
use think\Db;

/**
 * 页面接口管理
 * Class Page
 * @package app\store\controller\api
 */
class Page extends Controller
{

    /**
     * 获取微信商品首页
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function gets()
    {
        $where = ['is_deteted' => '0', 'status' => '1'];
        $list = Db::name('StorePage')->where($where)->order('sort asc,id desc')->select();
        foreach ($list as &$vo) {
            $vo['one'] = json_decode($vo['one']);
            $vo['mul'] = json_decode($vo['mul']);
        }
        $this->success('获取页面列表成功！', ['list' => $list]);
    }
}