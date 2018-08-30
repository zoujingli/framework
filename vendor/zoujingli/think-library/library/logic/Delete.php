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

use think\Db;
use think\db\Query;

/**
 * 通用删除管理器
 * Class ViewDelete
 * @package library\view
 */
class Delete extends Logic
{

    /**
     * 表单额外更新条件
     * @var array
     */
    protected $where;

    /**
     * 数据对象主键名称
     * @var string
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
     */
    public function __construct($dbQuery, $pkField = '', $where = [])
    {
        parent::__construct($dbQuery);
        // 传入的参数赋值处理
        $this->where = $where;
        // 获取表单主键的名称
        $this->pkField = empty($pkField) ? ($this->db->getPk() ? $this->db->getPk() : 'id') : $pkField;
        // 从where中获取主键的默认值
        $pkValue = isset($where[$this->pkField]) ? $where[$this->pkField] : null;
        $this->pkValue = $this->request->request($this->pkField, $pkValue);
    }

    /**
     * 组件初始化
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * @return boolean|null
     */
    protected function init()
    {
        // 主键限制条件处理
        $map = isset($this->where[$this->pkField]) ? [] :
            (is_array($this->pkValue) ? [$this->pkField, 'in', $this->pkValue] :
                (is_string($this->pkValue) ? [$this->pkField, 'in', explode(',', $this->pkValue)] :
                    [$this->pkField => $this->pkValue]));
        // 删除前置回调处理
        if (false === $this->class->_callback('_delete_filter', $map, $where)) {
            return null;
        }
        if (method_exists($this->db, 'getTableFields') && in_array('is_deleted', $this->db->getTableFields())) {
            // 软删除操作
            $result = Db::table($this->db->getTable())->where($this->where)->where($map)->update(['is_deleted' => '1']);
        } else {
            // 硬删除操作
            $result = Db::table($this->db->getTable())->where($this->where)->where($map)->delete();
        }
        // 删除结果回调处理
        if (false !== $this->class->_callback('_delete_result', $result)) {
            if ($result !== false) {
                $this->class->success('恭喜, 数据保存成功!', '');
            }
            $this->class->error('数据保存失败, 请稍候再试!');
        }
        return $result;
    }

}