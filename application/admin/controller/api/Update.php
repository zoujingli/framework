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

use app\admin\logic\Update as UpdateLogic;
use library\Controller;

/**
 * 系统更新接口
 * Class Update
 * @package app\admin\controller\api
 */
class Update extends Controller
{
    /**
     * 基础URL地址
     * @var string
     */
    protected $baseUri = 'https://framework.thinkadmin.top';

    /**
     * 获取文件列表
     */
    public function tree()
    {
        $this->success('获取当前文件列表成功！', UpdateLogic::tree([
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
     * 读取线上文件数据
     * @param string $encode
     */
    public function read($encode)
    {
        $file = env('root_path') . decode($encode);
        dump($file);
        if (file_exists($file)) $this->error('获取文件内容失败！');
        $content = base64_encode(file_get_contents($file));
        $this->success('获取文件内容成功！', ['format' => 'base64', 'content' => $content]);
    }

    public function down($encode)
    {
        dump("{$this->baseUri}?s=admin/api.update/read/{$encode}");
        $result = http_get("{$this->baseUri}?s=admin/api.update/read/{$encode}");
        dump($result);
    }

    public function sync()
    {
        foreach ($this->_diff() as $file) {

            $this->down(encode($file['name']));
            exit;
//            $path = realpath(env('root_path') . $file['name']);
//            switch ($file['type']) {
//                case 'add':
//                    $aa = $this->read(encode($file['name']));
//                    dump($aa);
//                    break;
//                case 'mod':
//                    break;
//                case 'del':
//
//                    break;
//
//            }

        }
    }

    /**
     * 获取文件差异
     */
    public function diff()
    {
        $this->success('获取文件比对差异成功！', $this->_diff());
    }

    /**
     * 获取文件差异数据
     * @return array
     */
    private function _diff()
    {
        $result = json_decode(http_get("{$this->baseUri}?s=/admin/api.update/tree"), true);
        if (empty($result['code'])) $this->error('获取比对文件差异失败！');
        $new = UpdateLogic::tree($result['data']['paths'], $result['data']['ignores']);
        return UpdateLogic::contrast($result['data']['list'], $new['list']);
    }

}