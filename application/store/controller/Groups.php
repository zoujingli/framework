<?php

namespace app\store\controller;

use library\Controller;
use think\Db;

/**
 * 团购管理
 * Class Groups
 * @package app\store\controller
 */
class Groups extends Controller
{

    /**
     * 指定当前数据表
     * @var string
     */
    protected $table = 'StoreGroups';

    /**
     * 团购列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '团购管理';
        return $this->_query($this->table)->where(['is_deleted' => '0'])->order('sort asc,id desc')->page();
    }

    /**
     * 添加团购信息
     * @return mixed
     */
    public function add()
    {
        $this->title = '添加团购信息';
        return $this->_form($this->table, 'form');
    }

    /**
     * 编辑团购信息
     * @return mixed
     */
    public function edit()
    {
        $this->title = '编辑团购信息';
        return $this->_form($this->table, 'form');
    }

    /**
     * 团购表单数据处理
     * @param array $vo
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    protected function _form_filter(&$vo)
    {
        if (empty($vo['id'])) $vo['id'] = date('YmdHis') . rand(1000, 9999);
        if ($this->request->isGet()) {
            // 读取所有可用的卡券
            $this->CardList = Db::name('StoreCard')->where(['is_deleted' => '0'])->order('id desc')->select();
            // 已有套餐的卡券处理
            $this->VoCardList = Db::name('StoreGroupsList')->field('cid id,cid,number')->where(['gid' => $vo['id']])->select();
            $clist = Db::name('StoreCard')->whereIn('id', array_unique(array_column($this->VoCardList, 'cid')))->select();
            foreach ($this->VoCardList as &$vo) {
                list($vo['bgimg'], $vo['name']) = ['', ''];
                foreach ($clist as $card) if ($card['id'] === $vo['cid']) {
                    list($vo['id'], $vo['name'], $vo['bgimg']) = [$card['id'], $card['name'], $card['bgimg']];
                }
            }
        }
        if ($this->request->isPost()) {
            list($data, $post) = [[], $this->request->post()];
            foreach (array_keys($this->request->post('card_id')) as $k) array_push($data, [
                'gid' => $vo['id'], 'price' => $post['card_price'][$k],
                'cid' => $post['card_id'][$k], 'number' => $post['card_number'][$k],
            ]);
            Db::name('StoreGroupsList')->where(['gid' => $vo['id']])->delete();
            Db::name('StoreGroupsList')->insertAll($data);
        }
    }

    /**
     * 修改成功后的提示
     * @param boolean $result
     */
    protected function _form_result($result)
    {
        if ($result) {
            $this->success('团购编辑成功！', 'javascript:history.back()');
        }
    }

    /**
     * 团购禁用
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 团购禁用
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 删除团购
     */
    public function del()
    {
        $this->_delete($this->table);
    }
}