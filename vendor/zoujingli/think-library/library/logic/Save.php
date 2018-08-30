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

use think\db\Query;
use library\tools\Data;

/**
 * 数据更新插件管理器
 * Class ViewSave
 * @package library\view
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
     * ViewForm constructor.
     * @param string|Query $dbQuery
     * @param string $pkField 指定数据对象主键
     * @param array $where 额外更新条件
     * @param array $data 表单扩展数据
     */
    public function __construct($dbQuery, $data = [], $pkField = '', $where = [])
    {
        parent::__construct($dbQuery);
        // 传入的参数赋值处理
        list($this->where, $this->data) = [$where, empty($data) ? $this->request->post() : []];
        // 获取表单主键的名称
        $this->pkField = empty($pkField) ? ($this->db->getPk() ? $this->db->getPk() : 'id') : $pkField;;
        // 从extend中获取主键的默认值
        if (!isset($this->data[$this->pkField])) {
            $pkField = isset($data[$this->pkField]) ? $data[$this->pkField] : null;
            $this->data[$this->pkField] = $this->request->request($this->pkField, $pkField);
        }
    }

    /**
     * 组件应用器
     * @return boolean
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    protected function init()
    {
        if (false !== $this->class->_callback('_save_filter', $this->data, $this->where)) {
            $result = Data::save($this->db, $this->data, $this->pkField, $this->where);
            if (false === $this->class->_callback('_save_result', $result)) {
                return $result;
            }
            if ($result !== false) {
                $this->class->success('数据记录保存成功!', '');
            }
            $this->class->error('数据保存失败, 请稍候再试!');
        }
    }

}