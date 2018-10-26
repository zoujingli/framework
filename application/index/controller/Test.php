<?php

// +----------------------------------------------------------------------
// | framework
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://framework.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/framework
// +----------------------------------------------------------------------

namespace app\index\controller;

use app\admin\logic\File;
use library\Controller;

/**
 * Class File
 * @package app\index\controller
 */
class Test extends Controller
{
    /**
     * 文件测试
     */
    public function file()
    {

        echo '<br>- down -<br>';
        $info = File::down('http://static.ctolog.com/test.txt');
        dump($info);

        echo '<br>- local -<br>';
        $oss = File::instance('local');
        $info = $oss->save('test.txt', 'tqwtqwteqwtq');
        dump($info);
        $info = $oss->info('test.txt');
        dump($info);

        echo '<br>- qiniu -<br>';
        $oss = File::instance('qiniu');
        $info = $oss->save('test.txt', 'tqwtqwteqwtq');
        dump($info);
        $info = $oss->info('test.txt');
        dump($info);

        echo '<br>- oss -<br>';
        $oss = File::instance('oss');
        $info = $oss->save('test.txt', 'tqwtqwteqwtq');
        dump($info);
        $info = $oss->info('test.txt');
        dump($info);
    }

}