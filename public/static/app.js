
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

// 当前资源URL目录
var baseRoot = (function () {
    var scripts = document.scripts, src = scripts[scripts.length - 1].src;
    return src.substring(0, src.lastIndexOf("/") + 1);
})();

// 配置参数
require.config({
    waitSeconds: 60,
    baseUrl: baseRoot,
    map: {'*': {css: baseRoot + 'plugs/require/css.js'}},
    paths: {
        'json': ['plugs/jquery/json2.min'],
        'base64': ['plugs/jquery/base64.min'],
        'angular': ['plugs/angular/angular.min'],
        'ckeditor': ['plugs/ckeditor/ckeditor'],
        'websocket': ['plugs/socket/websocket'],
        'pcasunzips': ['plugs/jquery/pcasunzips'],
        'jquery.ztree': ['plugs/ztree/ztree.all.min'],
        'jquery.masonry': ['plugs/jquery/masonry.min'],
    },
    shim: {
        'websocket': {deps: [baseRoot + 'plugs/socket/swfobject.min.js']},
        'jquery.ztree': {deps: ['css!' + baseRoot + 'plugs/ztree/zTreeStyle/zTreeStyle.css']},
    },
    deps: ['json'],
    // 开启debug模式，不缓存资源
    // urlArgs: "ver=" + (new Date()).getTime()
});

// 注册jquery到require模块
define('jquery', function () {
    return layui.$;
});

// 框架初始化
AdminLayout.call(window);

// 框架布局
function AdminLayout(callback, custom) {
    window.WEB_SOCKET_SWF_LOCATION = baseRoot + "plugs/socket/WebSocketMain.swf";
    require(custom || [], callback || false);
}