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

// 系统权限中间键
\think\facade\Middleware::add('app\admin\logic\Auth');

// 注册系统更新指令
\think\Console::addDefaultCommands(['app\admin\logic\Update']);

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
            return empty($json) ? [] : $json;
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