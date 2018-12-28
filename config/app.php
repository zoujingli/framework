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

foreach (scandir($_path_ = env('app_path')) as $dir) {
    if (stripos($dir, '.') === 0) continue;
    $init = $_path_ . $dir . DIRECTORY_SEPARATOR . "init.php";
    if (file_exists($init)) include_once($init);
}
return [
    // 应用调试模式
    'app_debug'      => true,
    // 应用Trace调试
    'app_trace'      => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type' => 1,
];
