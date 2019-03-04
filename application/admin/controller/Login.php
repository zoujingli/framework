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
use think\Db;

/**
 * 用户登录管理
 * Class Login
 * @package app\admin\controller
 */
class Login extends Controller
{

    /**
     * 用户登录
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '用户登录';
        if ($this->request->isGet()) {
            $this->fetch();
        } else {
            $data = $this->_input([
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
            if (empty($user)) $this->error('登录账号不存在，请重新输入!');
            if ($user['password'] !== md5($data['password'])) {
                $this->error('登录密码与账号不匹配，请重新输入!');
            }
            if (empty($user['status'])) $this->error('账号已经被禁用，请联系管理!');
            Db::name('SystemUser')->where(['id' => $user['id']])->update([
                'login_at'  => Db::raw('now()'),
                'login_ip'  => $this->request->ip(),
                'login_num' => Db::raw('login_num+1'),
            ]);
            session('user', $user);
            empty($user['authorize']) || \app\admin\service\Auth::applyNode();
            _syslog('系统管理', '用户登录系统成功');
            $this->success('登录成功，正在进入系统...', url('@admin'));
        }
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