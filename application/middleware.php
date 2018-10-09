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

use app\admin\logic\Auth;
use library\tools\Node;
use think\Db;

return [function (\think\Request $request, \Closure $next) {
    list($module, $controller, $action) = [$request->module(), $request->controller(), $request->action()];
    $node = Node::parseString("{$module}/{$controller}/{$action}");
    $info = Db::name('SystemNode')->cache(true, 30)->where(['node' => $node])->find();
    $access = ['is_auth' => $info['is_auth'], 'is_login' => $info['is_auth'] ? 1 : $info['is_login']];
    // 登录状态检查
    if (!empty($access['is_login']) && !session('user')) {
        $msg = ['code' => 0, 'msg' => '抱歉，您还没有登录获取访问权限！', 'url' => url('@admin/login')];
        return $request->isAjax() ? json($msg) : redirect($msg['url']);
    }
    // 访问权限检查
    if (!empty($access['is_auth']) && !Auth::checkAuthNode($node)) {
        return json(['code' => 0, 'msg' => '抱歉，您没有访问该模块的权限！']);
    }
    return $next($request);
}];