<?php

namespace app\index\controller;

use library\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->redirect('@admin');
    }

}
