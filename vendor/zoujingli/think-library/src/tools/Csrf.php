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

namespace library\tools;

/**
 * 表单CSRF表单令牌
 * Class Csrf
 * @package library\tools
 */
class Csrf
{

    /**
     * 检查表单CSRF验证
     * @return boolean
     */
    public static function checkFormToken()
    {
        $field = input('csrf_token_name', '__token__');
        $csrf = session($field, '', 'csrf');
        if (empty($csrf['node'])) return false;
        if (empty($csrf['token'])) return false;
        if ($csrf['token'] !== input($field)) return false;
        if ($csrf['node'] !== Node::current()) return false;
        return true;
    }

    /**
     * 清理表单CSRF信息
     * @param string $name
     */
    public static function clearFormToken($name)
    {
        is_null($name) ? session(null, 'csrf') : session($name, null, 'csrf');
    }

    /**
     * 生成表单CSRF信息
     * @param null $node
     * @return array
     */
    public static function buildFormToken($node = null)
    {
        $token = md5(uniqid());
        $name = 'csrf_token_value_' . uniqid();
        if (is_null($node)) $node = Node::current();
        session($name, ['node' => $node, 'token' => $token], 'csrf');
        return ['name' => $name, 'token' => $token, 'node' => $node];
    }

    /**
     * 返回视图内容
     * @param string $tpl 模板名称
     * @param array $vars 模板变量
     * @param string $node CSRF授权节点
     */
    public static function fetchTemplate($tpl = '', $vars = [], $node = null)
    {
        throw new \think\exception\HttpResponseException(view($tpl, $vars, 200, function ($html) use ($node) {
            return preg_replace_callback('/<\/form>/i', function () use ($node) {
                $csrf = self::buildFormToken($node);
                return
                    "<input type='hidden' name='csrf_token_name' value='{$csrf['name']}'>" .
                    "<input type='hidden' name='{$csrf['name']}' value='{$csrf['token']}'>" .
                    "</form>";
            }, $html);
        }));
    }
}