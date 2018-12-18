<?php

namespace app\store\controller\api;

use library\Controller;
use think\Db;

/**
 * Class Door
 * @package app\store\controller\api
 */
class Door extends Controller
{
    /**
     * 获取门店列表
     */
    public function gets()
    {
        $where = ['status' => '1', 'is_deleted' => '0'];
        $field = 'id,name,code,logo,content,city,latlng,address';
        $result = $this->_page(Db::name('StoreDoor')->field($field)->where($where), true, false, false, 20);
        $this->success('获取门店列表成功！', $result);
    }

}