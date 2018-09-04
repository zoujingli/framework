<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://think.ctolog.com
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/ThinkAdmin
// +----------------------------------------------------------------------

namespace app\admin\logic;

use library\tools\Node;
use think\Db;
use think\Request;

/**
 * 权限验证逻辑器
 * Class Init
 * @package app\admin\logic
 */
class Init
{

    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function handle($request, \Closure $next)
    {
        list($module, $controller, $action) = [$request->module(), $request->controller(), $request->action()];
        $node = Node::parseString("{$module}/{$controller}/{$action}");
        $info = Db::name('SystemNode')->cache(true, 30)->where(['node' => $node])->find();
        $access = [
            'is_menu'  => intval(!empty($info['is_menu'])),
            'is_auth'  => intval(!empty($info['is_auth'])),
            'is_login' => empty($info['is_auth']) ? intval(!empty($info['is_login'])) : 1,
        ];
        // 登录状态检查
        if (!empty($access['is_login']) && !session('user')) {
            $msg = ['code' => 0, 'msg' => '抱歉，您还没有登录获取访问权限！', 'url' => url('@admin/login')];
            return $request->isAjax() ? json($msg) : redirect($msg['url']);
        }
        // 访问权限检查
        if (!empty($access['is_auth']) && !self::checkAuthNode($node)) {
            return json(['code' => 0, 'msg' => '抱歉，您没有访问该模块的权限！']);
        }
        // 模板常量声明
        app('view')->init(config('template.'))->assign(['classuri' => Node::parseString("{$module}/{$controller}")]);
        return $next($request);
    }

}
