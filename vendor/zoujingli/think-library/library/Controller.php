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
// | github 仓库地址 ：https://github.com/zoujingli/ThinkLibrary
// +----------------------------------------------------------------------

namespace library;

use library\tools\Cors;
use think\Exception;
use think\exception\HttpResponseException;

/**
 * 标准控制器基类
 * --------------------------------
 * Class Controller
 * @package library
 * --------------------------------
 * @method logic\Query _query($dbQuery)
 * @method array _input($data, $rule = [], $message = [])
 * @method mixed _delete($dbQuery, $pkField = '', $where = [])
 * @method mixed _save($dbQuery, $data = [], $pkField = '', $where = [])
 * @method array _page($dbQuery, $isPage = true, $isDisplay = true, $total = false)
 * @method mixed _form($dbQuery, $tplFile = '', $pkField = '', $where = [], $extendData = [])
 * --------------------------------
 * @author Anyon <zoujingli@qq.com>
 * @date 2018/08/10 11:31
 */
class Controller extends \stdClass
{

    /**
     * 当前请求对象
     * @var \think\Request
     */
    protected $request;

    /**
     * 当前数据对象
     * @var array
     */
    protected $_data = [];

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        Cors::optionsHandler();
        $this->request = request();
    }

    /**
     * 实例方法调用
     * @param string $method 函数名称
     * @param array $arguments 调用参数
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $arguments = [])
    {
        $className = "library\\logic\\" . ucfirst(ltrim($method, '_'));
        if (class_exists($className)) {
            $app = app($className, $arguments);
            return method_exists($app, 'apply') ? $app->apply($this) : $app;
        }
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $arguments);
        }
        throw new Exception('method not exists:' . get_class($this) . '->' . $method);
    }

    /**
     * 模板数据赋值
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    /**
     * 获取赋值数据
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    /**
     * 数据回调处理机制
     * @param string $name 回调方法名称
     * @param mixed $one 回调引用参数1
     * @param mixed $two 回调引用参数2
     * @return boolean
     */
    public function _callback($name, &$one, &$two = [])
    {
        $action = $this->request->action();
        $methods = [$name, "_{$action}{$name}"];
        foreach ($methods as $method) if (method_exists($this, $method)) {
            if (false === $this->$method($one, $two)) return false;
        }
        return true;
    }

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
        empty($this->_data) || $this->assign($this->_data);
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