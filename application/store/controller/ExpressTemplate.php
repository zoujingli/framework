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
     * 页面列表处理
     * @param array $data
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function _page_filter(&$data)
    {
        $ruleList = Db::name('StoreExpressTemplateRule')->whereIn('template_id', array_unique(array_column($data, 'id')))->select();
        foreach ($data as &$vo) {
            $vo['list'] = [];
            foreach ($ruleList as $rule) if ($rule['template_id'] === $vo['id']) $vo['list'][] = $rule;
        }
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
            $vo['list'] = Db::name('StoreExpressTemplateRule')->where(['template_id' => $vo['id']])->select();
            foreach ($vo['list'] as &$item) $item['rule'] = explode(',', $item['rule']);
        } else {
            list($list, $idxs) = [[], []];
            foreach (array_keys($vo) as $key) if (stripos($key, 'order_reduction_state_') !== false) {
                $idxs[] = str_replace('order_reduction_state_', '', $key);
            }
            foreach (array_unique($idxs) as $index) if (!empty($vo["rule_{$index}"])) $list[] = [
                'template_id'           => $vo['id'],
                'rule'                  => join(',', $vo["rule_{$index}"]),
                'order_reduction_state' => $vo["order_reduction_state_{$index}"],
                'order_reduction_price' => $vo["order_reduction_price_{$index}"],
                'first_number'          => $vo["first_number_{$index}"],
                'first_price'           => $vo["first_price_{$index}"],
                'next_number'           => $vo["first_number_{$index}"],
                'next_price'            => $vo["first_price_{$index}"],
                'is_default'            => $vo['is_default'],
            ];
            // 默认邮费模板
            if (!empty($vo['is_default'])) $list = [[
                'template_id'           => $vo['id'],
                'rule'                  => '默认邮费规则',
                'order_reduction_state' => $vo["order_reduction_state_0"],
                'order_reduction_price' => $vo["order_reduction_price_0"],
                'first_number'          => $vo["first_number_0"],
                'first_price'           => $vo["first_price_0"],
                'next_number'           => $vo["first_number_0"],
                'next_price'            => $vo["first_price_0"],
                'is_default'            => $vo['is_default'],
            ]];
            if (empty($list)) $this->error('请配置有效的邮费规则');
            Db::name('StoreExpressTemplateRule')->where(['template_id' => $vo['id']])->delete();
            Db::name('StoreExpressTemplateRule')->insertAll($list);
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