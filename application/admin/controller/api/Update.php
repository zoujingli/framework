<?php
/**
 * Created by PhpStorm.
 * User: Anyon
 * Date: 2018/12/5
 * Time: 17:39
 */

namespace app\admin\controller\api;


use library\Controller;

class Update extends Controller
{

    public function get()
    {
        $result = \app\admin\logic\Update::get([
            'application/admin',
            'application/wechat',
        ], [
            'application/index/controller/Index.php',
        ]);
        $result = \app\admin\logic\Update::contrast($result['list'], $result['list']);
        dump($result);

    }


}