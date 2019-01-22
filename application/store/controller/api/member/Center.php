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

namespace app\store\controller\api\member;

use app\store\controller\api\Member;

/**
 * 商品会员中心
 * Class Center
 * @package app\store\controller\api\member
 */
class Center extends Member
{
    /**
     * 修改会员资料
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function info()
    {
        $data = ['id' => $this->member['id']];
        if ($this->request->has('headimg', 'post', true)) {
            $data['headimg'] = $this->request->post('headimg');
        }
        if ($this->request->has('nickname', 'post', true)) {
            $data['nickname'] = emoji_encode($this->request->post('nickname'));
        }
        if ($this->request->has('username', 'post', true)) {
            $data['username'] = emoji_encode($this->request->post('username'));
        }
        if (empty($data)) $this->error('没有需要修改的数据哦！');
        if (data_save('StoreMember', $data, 'id')) {
            $this->success('会员资料更新成功！');
        }
        $this->error('会员资料更新失败，请稍候再试！');
    }

}