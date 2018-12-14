<?php
/**
 * Created by PhpStorm.
 * User: Anyon
 * Date: 2018/12/14
 * Time: 18:06
 */

namespace app\store\controller;

use library\Controller;

/**
 * 卡券管理
 * Class Card
 * @package app\store\controller
 */
class Card extends Controller
{
    /**
     * 指定当前数据表
     * @var string
     */
    protected $table = 'card_cate';

    /**
     * 设定卡券类型
     * @var array
     */
    public $types = ['服务券', '产品券'];

    public function index()
    {
        return $this->_page($this->table);

    }

    public function add()
    {

    }

    public function edit()
    {

    }


}