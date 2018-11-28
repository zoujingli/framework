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

namespace library\logic;

use library\Controller;

/**
 * 搜索条件处理器
 * Class Query
 * @package library\logic
 */
class Query extends Logic
{

    /**
     * Query constructor.
     * @param \think\db\Query|string $dbQuery
     * @throws \think\Exception
     */
    public function __construct($dbQuery)
    {
        $this->request = request();
        $this->query = scheme_db($dbQuery);
    }

    /**
     * 逻辑器初始化
     * @param Controller $controller
     * @return $this
     */
    public function init(Controller $controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * 获取当前Db操作对象
     * @return \think\db\Query
     */
    public function db()
    {
        return $this->query;
    }

    /**
     * 设置Like查询条件
     * @param string|array $fields 查询字段
     * @param string $inputType 输入类型 get|post
     * @return $this
     */
    public function like($fields, $inputType = 'get')
    {
        $data = $this->request->$inputType();
        foreach (is_array($fields) ? $fields : explode(',', $fields) as $field) {
            if (isset($data[$field]) && $data[$field] !== '') $this->query->whereLike($field, "%{$data[$field]}%");
        }
        return $this;
    }

    /**
     * 设置Equal查询条件
     * @param string|array $fields 查询字段
     * @param string $inputType 输入类型 get|post
     * @return $this
     */
    public function equal($fields, $inputType = 'get')
    {
        $data = $this->request->$inputType();
        foreach (is_array($fields) ? $fields : explode(',', $fields) as $field) {
            if (isset($data[$field]) && $data[$field] !== '') $this->query->where($field, "{$data[$field]}");
        }
        return $this;
    }

    /**
     * 设置IN区间查询
     * @param string $fields 查询字段
     * @param string $split 输入分隔符
     * @param string $inputType 输入类型 get|post
     * @return $this
     */
    public function in($fields, $split = ',', $inputType = 'get')
    {
        $data = $this->request->$inputType();
        foreach (is_array($fields) ? $fields : explode(',', $fields) as $field) {
            if (isset($data[$field]) && $data[$field] !== '') {
                $this->query->whereIn($field, explode($split, $data[$field]));
            }
        }
        return $this;
    }

    /**
     * 设置where条件
     * @param array $where
     * @return $this
     */
    public function where($where)
    {
        $this->query->where($where);
        return $this;
    }

    /**
     * 设置DateTime区间查询
     * @param string|array $fields 查询字段
     * @param string $split 输入分隔符
     * @param string $inputType 输入类型 get|post
     * @return $this
     */
    public function dateBetween($fields, $split = ' - ', $inputType = 'get')
    {
        $data = $this->request->$inputType();
        foreach (is_array($fields) ? $fields : explode(',', $fields) as $field) {
            if (isset($data[$field]) && $data[$field] !== '') {
                list($start, $end) = explode($split, $data[$field]);
                $this->query->whereBetween($field, ["{$start} 00:00:00", "{$end} 23:59:59"]);
            }
        }
        return $this;
    }

    /**
     * 设置区间查询
     * @param string|array $fields 查询字段
     * @param string $split 输入分隔符
     * @param string $inputType 输入类型 get|post
     * @return $this
     */
    public function valueBetween($fields, $split = ' ', $inputType = 'get')
    {
        $data = $this->request->$inputType();
        foreach (is_array($fields) ? $fields : explode(',', $fields) as $field) {
            if (isset($data[$field]) && $data[$field] !== '') {
                list($start, $end) = explode($split, $data[$field]);
                $this->query->whereBetween($field, ["{$start}", "{$end}"]);
            }
        }
        return $this;
    }
}