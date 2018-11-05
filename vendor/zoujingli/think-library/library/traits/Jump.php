<?php

// +----------------------------------------------------------------------
// | Library for ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://library.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 仓库地址 ：https://gitee.com/zoujingli/ThinkLibrary
// | github 仓库地址 ：https://github.com/zoujingli/ThinkLibrary
// +----------------------------------------------------------------------

namespace library\traits;

use library\tools\Cors;
use think\exception\HttpResponseException;

/**
 * 内容输出管理器
 * Trait Jump
 * @package library\traits
 */
trait Jump
{

    /**
     * 返回成功的操作
     * @param mixed $info 消息内容
     * @param array $data 返回数据
     * @param integer $code 返回代码
     */
    protected function success($info, $data = [], $code = 1)
    {
        $result = ['code' => $code, 'info' => $info, 'data' => $data];
        throw new HttpResponseException(json($result, 200, Cors::getRequestHeader()));
    }

    /**
     * 返回失败的请求
     * @param mixed $info 消息内容
     * @param array $data 返回数据
     * @param integer $code 返回代码
     */
    protected function error($info, $data = [], $code = 0)
    {
        $result = ['code' => $code, 'info' => $info, 'data' => $data];
        throw new HttpResponseException(json($result, 200, Cors::getRequestHeader()));
    }

    /**
     * URL重定向
     * @param string $url 重定向跳转链接
     * @param array $params 重定向链接参数
     * @param integer $code 重定向跳转代码
     */
    protected function redirect($url, $params = [], $code = 301)
    {
        throw new HttpResponseException(redirect($url, $params, $code));
    }

    /**
     * 返回视图内容
     * @param string $tpl 模板名称
     * @param array $vars 模板变量
     * @param array $config 引擎配置
     * @return mixed
     */
    protected function fetch($tpl = '', $vars = [], $config = [])
    {
        return app('view')->fetch($tpl, $vars, $config);
    }

    /**
     * 模板变量赋值
     * @access protected
     * @param  mixed $name 要显示的模板变量
     * @param  mixed $value 变量的值
     */
    protected function assign($name, $value = '')
    {
        app('view')->assign($name, $value);
    }

}