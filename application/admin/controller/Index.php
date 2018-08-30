<?php

namespace app\admin\controller;

use library\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch('', ['title' => '系统管理']);
    }

}