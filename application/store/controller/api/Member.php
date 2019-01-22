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

namespace app\store\controller\api;

use library\Controller;
use think\Db;

/**
 * 会员管理基类
 * Class Member
 * @package app\store\controller\api
 */
class Member extends Controller
{
    /**
     * 会员数据
     * @var array
     */
    protected $member;

    /**
     * 公众号OPENID
     * @var string
     */
    protected $openid;

    /**
     * Member constructor.
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function __construct()
    {
        parent::__construct();
        // 会员信息检查
        $mid = $this->request->post('mid');
        $openid = $this->request->post('openid');
        if (empty($mid)) $this->error('无效的会员ID参数！');
        if (empty($openid)) $this->error('无效的会员绑定的OPENID！');
        $where = ['id' => $mid, 'openid' => $openid];
        $this->member = Db::name('StoreMember')->where($where)->find();
        if (empty($this->member)) $this->error('无效的会员信息，请重新登录授权！');
        $this->member['nickname'] = emoji_decode($this->member['nickname']);
    }

}