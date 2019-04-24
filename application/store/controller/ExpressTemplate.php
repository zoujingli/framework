<?php

namespace app\store\controller;

use library\Controller;
use library\tools\Data;
use think\Db;

/**
 * 邮费模板管理
 * Class ExpressTemplate
 * @package app\store\controller
 */
class ExpressTemplate extends Controller
{
    /**
     * 指定数据表
     * @var string
     */
    protected $table = 'StoreExpressTemplate';

    /**
     * 邮费模板管理
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '邮费模板管理';
        $where = ['is_deleted' => '0', 'is_default' => '0'];
        $this->_query($this->table)->like('title,rule')->equal('status')->dateBetween('create_at')->where($where)->order('sort asc,id desc')->page();
    }

    /**
     * 添加邮费模板
     */
    public function add()
    {
        $this->title = '添加邮费模板';
        $this->_form($this->table, 'form');
    }

    /**
     * 编辑邮费模板
     */
    public function edit()
    {
        $this->title = '编辑邮费模板';
        $this->_form($this->table, 'form');
    }


    /**
     * 编辑默认邮费
     */
    public function defaults()
    {
        $this->title = '编辑默认邮费模板';
        $this->is_default = true;
        $where = ['is_default' => '1'];
        $this->_form($this->table, 'form', 'is_default', $where, $where);
    }

    /**
     * 表单数据处理
     * @param array $vo
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    protected function _form_filter(&$vo)
    {
        if (empty($vo['id'])) $vo['id'] = Data::uniqidNumberCode(10);
        if ($this->request->isGet()) {
            $where = [['code', 'like', '%0000'], ['status', 'eq', '1']];
            $this->provinces = Db::name('StoreExpressArea')->where($where)->order('code asc')->column('title');
            $vo['rule'] = isset($vo['rule']) ? explode(',', $vo['rule']) : [];
        } else {
            if (empty($vo['is_default'])) {
                if (isset($vo['rule']) && is_array($vo['rule'])) {
                    $vo['rule'] = join(',', $vo['rule']);
                } else {
                    $this->error('配置配送规则省份不能为空!');
                }
            }
        }
    }

    /**
     * 禁用邮费模板
     */
    public function forbid()
    {
        $this->applyCsrfToken();
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 启用邮费模板
     */
    public function resume()
    {
        $this->applyCsrfToken();
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 删除邮费模板
     */
    public function remove()
    {
        $this->applyCsrfToken();
        $this->_delete($this->table);
    }

}