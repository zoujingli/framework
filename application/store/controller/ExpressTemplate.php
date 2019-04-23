<?php

namespace app\store\controller;

use library\Controller;
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
        $this->_query($this->table)->like('title,rule')->equal('status')->dateBetween('create_at')->order('sort asc,id desc')->page();
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
     * 表单数据处理
     * @param array $vo
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function _form_filter(&$vo)
    {
        if ($this->request->isGet()) {
            $where = [['code', 'like', '%0000'], ['status', 'eq', '1']];
            $this->provinces = Db::name('StoreExpressArea')->where($where)->order('code asc')->column('title');
            $vo['rule'] = isset($vo['rule']) ? explode(',', $vo['rule']) : [];
        } else {
            if (isset($vo['rule']) && is_array($vo['rule'])) {
                $vo['rule'] = join(',', $vo['rule']);
            } else {
                $this->error('配置配送规则省份不能为空!');
            }
        }
    }

    /**
     * 表单结果处理
     * @param boolean $result
     */
    protected function _form_result($result)
    {
        if ($result) {
            $this->success('邮费模板修改成功！', 'javascript:history.back()');
        }
    }

    /**
     * 禁用邮费模板
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 启用邮费模板
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 删除邮费模板
     */
    public function remove()
    {
        $this->_delete($this->table);
    }

}