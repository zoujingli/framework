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
 * 表单视图管理器
 * Class ViewForm
 * @package library\view
 */
class Form extends Logic
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
     * 表单模板文件
     * @var string
     */
    protected $tplFile;

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
     * @param string $tplFile 模板名称
     * @param string $pkField 指定数据对象主键
     * @param array $where 额外更新条件
     * @param array $data 表单扩展数据
     */
    public function __construct($dbQuery, $tplFile = '', $pkField = '', $where = [], $data = [])
    {
        parent::__construct($dbQuery);
        // 传入的参数赋值处理
        list($this->tplFile, $this->where, $this->data) = [$tplFile, $where, $data];
        // 获取表单主键的名称
        $this->pkField = empty($pkField) ? ($this->db->getPk() ? $this->db->getPk() : 'id') : $pkField;;
        // 从where及extend中获取主键的默认值
        $pkValue = isset($data[$this->pkField]) ? $data[$this->pkField] : null;
        $this->pkValue = $this->request->request($this->pkField, $pkValue);
    }

    /**
     * 应用初始化
     * @return array|mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    protected function init()
    {
        // GET请求, 获取数据并显示表单页面
        if ($this->request->isGet()) {
            $data = [];
            if ($this->pkValue !== null) {
                $where = [$this->pkField => $this->pkValue];
                $data = (array)$this->db->where($where)->where($this->where)->find();
            }
            $data = array_merge($data, $this->data);
            if (false !== $this->class->_callback('_form_filter', $data)) {
                return $this->class->fetch($this->tplFile, ['vo' => $data]);
            }
            return $data;
        }
        // POST请求, 数据自动存库处理
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $data = array_merge($post, $this->data);
            if (false !== $this->class->_callback('_form_filter', $data, $this->where)) {
                $result = Data::save($this->db, $data, $this->pkField, $this->where);
                if (false !== $this->class->_callback('_form_result', $result, $data)) {
                    if ($result !== false) {
                        $this->class->success('恭喜, 数据保存成功!', '');
                    }
                    $this->class->error('数据保存失败, 请稍候再试!');
                }
            }
        }
    }

}