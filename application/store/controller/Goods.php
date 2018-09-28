<?php
/**
 * Created by PhpStorm.
 * User: Anyon
 * Date: 2018/9/27
 * Time: 11:21
 */

namespace app\store\controller;


use library\Controller;

class Goods extends Controller
{

    public function index()
    {
        return $this->fetch();
    }

    public function add()
    {
        $this->title = '添加商品信息';
        return $this->fetch('form');
    }

    public function edit()
    {
        return $this->fetch('form');
    }


}