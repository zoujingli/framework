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

/**
 * 节点访问权限检查
 * @param string $node
 * @return boolean
 */
function auth($node)
{
    return \app\admin\logic\Auth::checkAuthNode($node);
}

/**
 * UTF8字符串加密
 * @param string $string
 * @return string
 */
function encode($string)
{
    return \library\tools\Crypt::encode($string);
}

/**
 * UTF8字符串解密
 * @param string $string
 * @return string
 */
function decode($string)
{
    return \library\tools\Crypt::decode($string);
}

/**
 * 日期格式标准输出
 * @param string $datetime 输入日期
 * @param string $format 输出格式
 * @return false|string
 */
function format_datetime($datetime, $format = 'Y年m月d日 H:i:s')
{
    return date($format, strtotime($datetime));
}

/**
 * 打印输出数据到文件
 * @param mixed $data 输出的数据
 * @param boolean $force 强制替换
 * @param string|null $file 文件名称
 */
function p($data, $force = false, $file = null)
{
    is_null($file) && $file = env('runtime_path') . date('Ymd') . '.txt';
    $str = (is_string($data) ? $data : (is_array($data) || is_object($data)) ? print_r($data, true) : var_export($data, true)) . PHP_EOL;
    $force ? file_put_contents($file, $str) : file_put_contents($file, $str, FILE_APPEND);
}

/**
 * 设备或配置系统参数
 * @param string $name 参数名称
 * @param boolean $value 无值为获取
 * @return string|boolean
 * @throws \think\Exception
 * @throws \think\exception\PDOException
 */
function sysconf($name, $value = null)
{
    static $data = [];
    if ($value !== null) {
        list($row, $data) = [['name' => $name, 'value' => $value], []];
        return \library\tools\Data::save('SystemConfig', $row, 'name');
    }
    if (empty($data)) {
        $data = \think\Db::name('SystemConfig')->column('name,value');
    }
    return isset($data[$name]) ? $data[$name] : '';
}