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
 * 页面管理
 * Class Page
 * @package app\store\controller
 */
class Page extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'StorePage';

    /**
     * 页面管理
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '商城页面管理';
        $this->_query($this->table)->order('sort asc,id desc')->page();
    }

    /**
     * 数据列表处理
     * @param array $data
     */
    protected function _page_filter(&$data)
    {
        foreach ($data as &$vo) {
            $vo['data'] = json_decode($vo[$vo['type']]);
        }
    }

    /**
     * 添加页面
     */
    public function add()
    {
        $this->title = '添加商城页面';
        $this->_form($this->table, 'form');
    }

    /**
     * 编辑页面
     */
    public function edit()
    {
        $this->title = '编辑商城页面';
        $this->_form($this->table, 'form');
    }

    /**
     * 表单数据处理
     * @param array $post
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function _form_filter(&$post = [])
    {
        if ($this->request->isGet()) {
            $where = ['is_deleted' => '0', 'status' => '1'];
            $this->goodsList = Db::name('StoreGoods')->where($where)->order('sort asc,id desc')->select();
        } else foreach (['one', 'mul'] as $type) {
            if (empty($post[$type])) $post[$type] = [];
            $post[$type] = json_encode($post[$type], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 禁用页面
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 启用页面
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 删除页面
     */
    public function del()
    {
        $this->_delete($this->table);
    }

}