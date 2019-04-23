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
 * 商城区域管理
 * Class Area
 * @package app\store\controller
 */
class ExpressArea extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'StoreExpressArea';

    /**
     * 区域信息管理
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '区域信息管理';
        $this->_query($this->table)->like('title')->equal('level')->order('code asc')->page();
    }

    /**
     * 列表数据处理
     * @param array $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function _index_page_filter(&$data)
    {
        $lines = Db::name($this->table)->column('*', 'code');
        foreach ($lines as &$vo) {
            list($c1, $c2, $c3) = str_split($vo['code'], 2);
            if ($c2 . $c3 === '0000') { # 省份
                $vo['level'] = 1;
                $vo['parent'] = [];
            } elseif ($c3 === '00') { # 城市
                $vo['level'] = 2;
                $in = "{$c1}0000";
                $vo['parent'] = isset($lines[$in]) ? $lines[$in] : [];
            } else { # 区域
                $vo['level'] = 3;
                $in = "{$c1}{$c2}00";
                $vo['parent'] = isset($lines[$in]) ? $lines[$in] : [];
            }
        }
        foreach ($data as &$vo) foreach ($lines as $line) if ($line['id'] === $vo['id']) $vo = $line;
    }

    /**
     * 添加区域信息
     */
    public function add()
    {
        $this->applyCsrfToken();
        $this->_form($this->table, 'form');
    }

    /**
     * 编辑区域信息
     */
    public function edit()
    {
        $this->applyCsrfToken();
        $this->_form($this->table, 'form');
    }

    /**
     * 表单数据处理
     * @param array $vo
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function _form_filter(&$vo)
    {
        if ($this->request->isGet()) {
            $this->typedesc = '省份';
            if (empty($vo['pid'])) $vo['pid'] = input('pid', '0');
            $vo['parent'] = Db::name($this->table)->where(['id' => $vo['pid']])->find();
            if (!empty($vo['parent'])) {
                if ($vo['parent']['level'] === 1) $this->typedesc = '城市';
                if ($vo['parent']['level'] === 2) $this->typedesc = '区域';
            }
        }
    }

    /**
     * 启用区域信息
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function resume()
    {
        $this->applyCsrfToken();
        if (Db::name($this->table)->where($this->getWhere())->update(['status' => '1']) !== false) {
            $this->success('区域地址启用成功！');
        } else {
            $this->error('区域地址启用失败，请稍候再试！');
        }
    }

    /**
     * 禁用区域信息
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function forbid()
    {
        $this->applyCsrfToken();
        if (Db::name($this->table)->where($this->getWhere())->update(['status' => '0']) !== false) {
            $this->success('区域地址禁用成功！');
        } else {
            $this->error('区域地址禁用失败，请稍候再试！');
        }
    }

    /**
     * 删除区域信息
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function del()
    {
        $this->applyCsrfToken();
        if (Db::name($this->table)->where($this->getWhere())->delete() !== false) {
            $this->success('区域地址删除成功！');
        } else {
            $this->error('区域地址删除失败，请稍候再试！');
        }
    }

    /**
     * 获取表单条件
     * @return array
     */
    private function getWhere()
    {
        $ids = explode(',', $this->request->post('id', '0'));
        if (count($pids = Db::name($this->table)->whereIn('pid', $ids)->column('id')) > 0) {
            $pids = array_merge($pids, Db::name($this->table)->whereIn('pid', $pids)->column('id'));
        }
        return [['id', 'in', array_unique(array_merge($pids, $ids))]];
    }

}