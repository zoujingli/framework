<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://framework.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/ThinkAdmin
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\logic\Auth;
use library\Controller;
use library\tools\Data;
use think\Db;

/**
 * 后台入口管理
 * Class Index
 * @package app\admin\controller
 */
class Index extends Controller
{

    /**
     * 显示后台首页
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $menus = Auth::getAuthMenu();
        if (empty($menus) && !session('user.id')) {
            $this->redirect('@admin/login');
        }
        return $this->fetch('', ['menus' => $menus, 'title' => '系统管理']);
    }

    /**
     * 后台环境信息
     * @return mixed
     */
    public function main()
    {
        $version = Db::query('select version() as ver');
        return $this->fetch('', [
            'title'     => '后台首页',
            'think_ver' => \think\App::VERSION,
            'mysql_ver' => array_pop($version)['ver'],
        ]);
    }

    /**
     * 修改密码
     * @param integer $id
     * @return array|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function pass($id)
    {
        if (intval($id) !== intval(session('user.id'))) {
            $this->error('只能修改当前用户的密码！');
        }
        if ($this->request->isGet()) {
            $this->assign(['verify' => true]);
            return $this->_form('SystemUser', 'user/pass');
        }
        // 获取输入数据
        $data = $this->_input([
            'password'    => $this->request->post('password'),
            'repassword'  => $this->request->post('repassword'),
            'oldpassword' => $this->request->post('oldpassword'),
        ], [
            'oldpassword' => 'require',
            'password'    => 'require|min:4',
            'repassword'  => 'require|confirm:password',
        ], [
            'oldpassword.require' => '旧密码不能为空！',
            'password.require'    => '登录密码不能为空！',
            'password.min'        => '登录密码长度不能少于4位有效字符！',
            'repassword.require'  => '重复密码不能为空！',
            'repassword.confirm'  => '重复密码与登录密码不匹配，请重新输入！',
        ]);
        $user = Db::name('SystemUser')->where(['id' => $id])->find();
        if (md5($data['oldpassword']) !== $user['password']) {
            $this->error('旧密码验证失败，请重新输入！');
        }
        if (Data::save('SystemUser', ['id' => $user['id'], 'password' => md5($data['password'])])) {
            $this->success('密码修改成功，下次请使用新密码登录！', '');
        }
        $this->error('密码修改失败，请稍候再试！');
    }

    /**
     * 修改用户资料
     * @param integer $id 会员ID
     * @return array|string
     */
    public function info($id = 0)
    {
        if (intval($id) === intval(session('user.id'))) {
            return $this->_form('SystemUser', 'user/form', 'id', [], ['id' => $id]);
        }
        $this->error('只能修改登录用户的资料！');
    }

}