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

namespace app\admin\controller\api;

use library\Controller;

/**
 * 系统更新接口
 * Class Update
 * @package app\admin\controller\api
 */
class Update extends Controller
{

    /**
     * 获取文件列表
     */
    public function get()
    {
        $this->success('获取当前文件列表成功！', \app\admin\logic\Update::get([
            'application/wechat',
            'application/admin',
            'public/static/plugs',
            'public/static/theme',
            'public/static/admin.js',
        ], [
            'application/admin/view/login/index.html',
        ]));
    }

    /**
     * 获取文件差异
     */
    public function diff()
    {
        $result = json_decode(http_get('https://framework.thinkadmin.top/admin/api.update/get'), true);
        $data = $result['data'];
        $new = \app\admin\logic\Update::get($data['paths'], $data['ignores']);
        $diff = \app\admin\logic\Update::contrast($result['data']['list'], $new['list']);
        $this->success('获取更新文件差异成功！', $diff);
    }

}