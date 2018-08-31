<?php

namespace app\admin\controller;

use library\Controller;
use think\Db;

class Login extends Controller
{
    /**
     * 用户登录
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        if ($this->request->isGet()) {
            return $this->fetch('', ['title' => '用户登录']);
        }
        // 数据输入处理
        $data = $this->_validate([
            'username' => $this->request->post('username'),
            'password' => $this->request->post('password'),
        ], [
            'username' => 'require|min:4',
            'password' => 'require|min:4',
        ], [
            'username.require' => '登录账号不能为空！',
            'password.require' => '登录密码不能为空！',
            'username.min'     => '登录账号长度不能少于4位有效字符！',
            'password.min'     => '登录密码长度不能少于4位有效字符！',
        ]);
        // 用户信息验证
        $map = ['is_deleted' => '0', 'username' => $data['username']];
        $user = Db::name('SystemUser')->where($map)->find();
        empty($user) && $this->error('登录账号不存在，请重新输入!');
        if ($user['password'] !== md5($data['password'])) {
            $this->error('登录密码与账号不匹配，请重新输入!');
        }
        empty($user['status']) && $this->error('账号已经被禁用，请联系管理!');
        // 更新登录信息
        Db::name('SystemUser')->where(['id' => $user['id']])->update([
            'login_at'  => Db::raw('now()'),
            'login_num' => Db::raw('login_num+1'),
        ]);
        session('user', $user);
        $this->success('登录成功，正在进入系统...', url('@admin'));
    }

    /**
     * 退出登录
     */
    public function out()
    {
        empty($_SESSION) || $_SESSION = [];
        [session_unset(), session_destroy()];
        $this->success('退出登录成功！', url('@admin/login'));
    }

}