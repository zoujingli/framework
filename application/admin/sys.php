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

if (!function_exists('auth')) {
    /**
     * 节点访问权限检查
     * @param string $node
     * @return boolean
     */
    function auth($node)
    {
        return \app\admin\service\Auth::checkAuthNode($node);
    }
}

if (!function_exists('_syslog')) {
    /**
     * 写入系统日志
     * @param string $action 日志行为
     * @param string $content 日志内容
     * @return boolean
     */
    function _syslog($action, $content)
    {
        return \app\admin\service\Log::write($action, $content);
    }
}

if (!function_exists('sysdata')) {
    /**
     * JSON 数据读取与存储
     * @param string $name 数据名称
     * @param array|null $value 数据内容
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    function sysdata($name, array $value = null)
    {
        if (is_null($value)) {
            $data = json_decode(\think\Db::name('SystemData')->where('name', $name)->value('value'), true);
            return empty($data) ? [] : $data;
        }
        return data_save('SystemData', ['name' => $name, 'value' => json_encode($value, 256)], 'name');
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
        $result = \library\File::down($url);
        if (isset($result['url'])) return $result['url'];
        return $url;
    }
}

if (!function_exists('base64_image')) {
    /**
     * base64 图片上传接口
     * @param string $content
     * @return string
     */
    function base64_image($content)
    {
        try {
            if (preg_match('|^data:image/(.*?);base64,|i', $content)) {
                list($ext, $base) = explode('|||', preg_replace('|^data:image/(.*?);base64,|i', '$1|||', $content));
                $info = \library\File::save('base64image/' . md5($base) . '.' . (empty($ext) ? 'tmp' : $ext), base64_decode($base));
                return $info['url'];
            }
            return $content;
        } catch (\Exception $e) {
            return $content;
        }
    }
}

// 注册中间键
\think\facade\Middleware::add('app\admin\service\Auth');

// 注册系统指令
\think\Console::addDefaultCommands([
    'app\admin\command\Session',
    'app\admin\command\Composer',
    'app\admin\command\task\TaskStop',
    'app\admin\command\task\TaskState',
    'app\admin\command\task\TaskStart',
    'app\admin\command\task\TaskReset',
    'app\admin\command\update\UpdateAdmin',
    'app\admin\command\update\UpdatePlugs',
    'app\admin\command\update\UpdateConfig',
    'app\admin\command\update\UpdateWechat',
    'app\admin\command\update\UpdateService',
]);