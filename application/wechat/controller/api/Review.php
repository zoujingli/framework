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

namespace app\wechat\controller\api;

use app\wechat\logic\Media;
use library\Controller;
use think\Db;

class Review extends Controller
{

    /**
     * 显示图文列表
     * @param integer $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function news($id)
    {
        $this->news = Media::news($id);
        return $this->fetch();
    }

    /**
     * 显示图文内容
     * @param integer $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function view($id)
    {
        $this->info = Db::name('WechatNewsArticle')->where(['id' => $id])->find();
        return $this->fetch();
    }

}