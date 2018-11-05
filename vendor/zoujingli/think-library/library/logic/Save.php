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

namespace library\logic;

use think\db\Query;

/**
 * 数据更新管理器
 * Class Save
 * @package library\logic
 */
class Save extends Logic
{
    /**
     * 表单扩展数据
     * @var array
     */
    protected $data;

    /**
     * 表单额外更新条件
     * @var array
     */
    protected $where;

    /**
     * 数据对象主键名称
     * @var array|string
     */
    protected $pkField;

    /**
     * 数据对象主键值
     * @var string
     */
    protected $pkValue;

    /**
     * ViewForm constructor.
     * @param string|Query $dbQuery
     * @param string $pkField 指定数据对象主键
     * @param array $where 额外更新条件
     * @param array $data 表单扩展数据
     */
    public function __construct($dbQuery, $data = [], $pkField = '', $where = [])
    {
        parent::__construct($dbQuery);
        $this->where = $where;
        $this->data = empty($data) ? $this->request->post() : $data;
        $this->pkField = empty($pkField) ? $this->db->getPk() : $pkField;
        $this->pkValue = $this->request->post($this->pkField, null);
    }

    /**
     * 组件应用器
     * @return boolean
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    protected function init()
    {
        // 主键限制处理
        if (!isset($this->where[$this->pkField]) && is_string($this->pkValue)) {
            $this->db->whereIn($this->pkField, explode(',', $this->pkValue));
            if (isset($this->data)) unset($this->data[$this->pkField]);
        }
        // 前置回调处理
        if (false === $this->class->_callback('_save_filter', $this->db, $this->data)) {
            return false;
        }
        // 执行更新操作
        $result = $this->db->where($this->where)->update($this->data) !== false;
        // 结果回调处理
        if (false === $this->class->_callback('_save_result', $result)) {
            return $result;
        }
        // 回复前端结果
        if ($result !== false) {
            $this->class->success('数据记录保存成功!', '');
        }
        $this->class->error('数据保存失败, 请稍候再试!');
    }

}