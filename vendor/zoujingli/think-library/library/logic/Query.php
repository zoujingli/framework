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
// | github开源项目：https://github.com/zoujingli/ThinkLibrary
// +----------------------------------------------------------------------

namespace library\logic;

/**
 * 搜索条件处理器
 * Class Query
 * @package library\logic
 */
class Query extends Logic
{

    /**
     * 应用初始化
     * @return $this
     */
    protected function init()
    {
        return $this;
    }

    /**
     * 获取当前Db操作对象
     * @return \think\db\Query
     */
    public function db()
    {
        return $this->db;
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
            if (isset($data[$field]) && $data[$field] !== '') {
                $this->db->whereLike($field, "%{$data[$field]}%");
            }
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
            if (isset($data[$field]) && $data[$field] !== '') {
                $this->db->where($field, "{$data[$field]}");
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
        $this->db->where($where);
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
                $this->db->whereBetween($field, ["{$start} 00:00:00", "{$end} 23:59:59"]);
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
                $this->db->whereBetween($field, ["{$start}", "{$end}"]);
            }
        }
        return $this;
    }
}