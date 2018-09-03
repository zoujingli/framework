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

use app\admin\Logic\Auth;
use library\Controller;
use library\tools\Data;
use think\App;
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
        return $this->fetch('', ['title' => '系统管理', 'menus' => $menus]);
    }

    /**
     * rgck
     * @return mixed
     */
    public function main()
    {
        $_version = Db::query('select version() as ver');
        return $this->fetch('', [
            'title'     => '后台首页',
            'think_ver' => App::VERSION,
            'mysql_ver' => array_pop($_version)['ver'],
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
            $this->assign('verify', true);
            $this->assign('vo', session('user'));
            dump(session('user'));
            return $this->_form('SystemUser', 'user/pass');
        }
        $data = $this->request->post();
        if ($data['password'] !== $data['repassword']) {
            $this->error('两次输入的密码不一致，请重新输入！');
        }
        $user = Db::name('SystemUser')->where('id', session('user.id'))->find();
        if (md5($data['oldpassword']) !== $user['password']) {
            $this->error('旧密码验证失败，请重新输入！');
        }
        if (Data::save('SystemUser', ['id' => session('user.id'), 'password' => md5($data['password'])])) {
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
        $this->error('只能修改当前用户的资料！');
    }

}