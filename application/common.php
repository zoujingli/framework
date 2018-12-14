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

// 注册系统更新指令
\think\Console::addDefaultCommands(['app\admin\logic\Update']);

// 系统权限中间键
\think\facade\Middleware::add(function (\think\Request $request, \Closure $next) {
    $map = ['node' => ($node = \library\tools\Node::current())];
    $auth = \think\Db::name('SystemNode')->cache(true, 30)->field('is_auth,is_login')->where($map)->find();
    $access = ['is_auth' => $auth['is_auth'], 'is_login' => $auth['is_auth'] ? 1 : $auth['is_login']];
    // 登录状态检查
    if (!empty($access['is_login']) && !\app\admin\logic\Auth::isLogin()) {
        $message = ['code' => 0, 'msg' => '抱歉，您还没有登录获取访问权限！', 'url' => url('@admin/login')];
        return $request->isAjax() ? json($message) : redirect($message['url']);
    }
    // 访问权限检查
    if (!empty($access['is_auth']) && !\app\admin\logic\Auth::checkAuthNode($node)) {
        return json(['code' => 0, 'msg' => '抱歉，您没有访问该模块的权限！']);
    }
    return $next($request);
});

if (!function_exists('auth')) {
    /**
     * 节点访问权限检查
     * @param string $node
     * @return boolean
     */
    function auth($node)
    {
        return \app\admin\logic\Auth::checkAuthNode($node);
    }
}

if (!function_exists('sysdata')) {
    /**
     * JSON 数据读取与存储
     * @param string $name
     * @param array|null $value
     * @return array|null|boolean
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    function sysdata($name, array $value = null)
    {
        if (is_null($value)) {
            $json = json_decode(emoji_decode(\think\Db::name('SystemData')->where('name', $name)->value('value')), true);
            return empty($json) ? null : $json;
        }
        return data_save('SystemData', ['name' => $name, 'value' => emoji_encode(json_encode($value, 256))], 'name');
    }
}

if (!function_exists('local_image')) {
    /**
     * 下载远程文件到本地
     * @param string $url 远程图片地址
     * @return string
     */
    function local_image($url)
    {
        return \library\File::down($url)['url'];
    }
}