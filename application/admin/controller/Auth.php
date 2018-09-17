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

namespace app\admin\controller;

use library\Controller;
use library\tools\Data;
use think\Db;

/**
 * 权限管理
 * Class Auth
 * @package app\admin\controller
 */
class Auth extends Controller
{
    /**
     * 默认数据模型
     * @var string
     */
    public $table = 'SystemAuth';

    /**
     * 权限列表
     * @return array|string
     */
    public function index()
    {
        $this->title = '系统权限管理';
        return $this->_page($this->table);
    }

    /**
     * 权限授权
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function apply()
    {
        $this->title = '节点授权';
        $auth = $this->request->post('id', '0');
        switch ($this->request->post('action', '', 'strtolower')) {
            case 'get':
                return $this->_apply_get($auth);
            case 'save':
                return $this->_apply_save($auth);
            default:
                return $this->_form($this->table, 'apply');
        }
    }

    /**
     * 读取授权节点
     * @param string $auth
     */
    private function _apply_get($auth)
    {
        $nodes = \app\admin\logic\AuthLogic::get();
        $checked = Db::name('SystemAuthNode')->where(['auth' => $auth])->column('node');
        foreach ($nodes as &$node) {
            $node['checked'] = in_array($node['node'], $checked);
        }
        $all = $this->_apply_filter(Data::arr2tree($nodes, 'node', 'pnode', '_sub_'));
        $this->success('获取节点成功！', $all);
    }

    /**
     * 保存授权节点
     * @param string $auth
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    private function _apply_save($auth)
    {
        list($data, $post) = [[], $this->request->post()];
        foreach (isset($post['nodes']) ? $post['nodes'] : [] as $node) {
            $data[] = ['auth' => $auth, 'node' => $node];
        }
        Db::name('SystemAuthNode')->where(['auth' => $auth])->delete();
        Db::name('SystemAuthNode')->insertAll($data);
        $this->success('节点授权更新成功！', '');
    }

    /**
     * 节点数据拼装
     * @param array $nodes
     * @param int $level
     * @return array
     */
    protected function _apply_filter($nodes, $level = 1)
    {
        foreach ($nodes as $key => $node) {
            if (!empty($node['_sub_']) && is_array($node['_sub_'])) {
                $node[$key]['_sub_'] = $this->_apply_filter($node['_sub_'], $level + 1);
            }
        }
        return $nodes;
    }

    /**
     * 权限添加
     * @return array|string
     */
    public function add()
    {
        return $this->_form($this->table, 'form');
    }

    /**
     * 权限编辑
     * @return array|string
     */
    public function edit()
    {
        return $this->_form($this->table, 'form');
    }

    /**
     * 权限禁用
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 权限恢复
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 权限删除
     */
    public function del()
    {
        $this->_delete($this->table);
    }

    /**
     * 删除结果处理
     * @param boolean $result
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    protected function _del_delete_result($result)
    {
        if ($result) {
            $where = ['auth' => $this->request->post('id')];
            Db::name('SystemAuthNode')->where($where)->delete();
            $this->success("权限删除成功！", '');
        }
        $this->error("权限删除失败，请稍候再试！");
    }

}