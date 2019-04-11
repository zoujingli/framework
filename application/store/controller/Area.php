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
class Area extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'StoreArea';

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
        $cityList = Db::name($this->table)->whereIn('id', array_unique(array_column($data, 'pid')))->order('sort asc,id desc')->select();
        $provList = Db::name($this->table)->whereIn('id', array_unique(array_column($cityList, 'pid')))->order('sort asc,id desc')->select();
        foreach ($cityList as &$vo) {
            $vo['parent'] = [];
            foreach ($provList as $prov) if ($prov['id'] === $vo['pid']) $vo['parent'] = $prov;
        }
        foreach ($data as &$vo) {
            $vo['parent'] = [];
            foreach ($cityList as $city) if ($city['id'] === $vo['pid']) {
                if ($city['level'] === 1) $vo['parent'] = ['parent' => $city];
                elseif ($city['level'] === 2) $vo['parent'] = $city;
            }
        }
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
            if (isset($vo['pid']) && $vo['pid'] > 0) {
                $vo['parent'] = Db::name($this->table)->where(['id' => $vo['pid']])->find();
                if (!empty($vo['parent'])) {
                    if ($vo['parent']['level'] === 1) $this->typedesc = '城市';
                    if ($vo['parent']['level'] === 2) $this->typedesc = '区域';
                }
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
        $id = $this->request->post('id', 0);
        if (count($pids = Db::name($this->table)->where(['pid' => $id])->column('id')) > 0) {
            $pids = array_merge($pids, Db::name($this->table)->whereIn('pid', $pids)->column('id'));
        }
        return [['id', 'in', array_unique(array_merge($pids, [$id]))]];
    }

}