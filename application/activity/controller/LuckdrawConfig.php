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

namespace app\activity\controller;

use library\Controller;
use think\Db;

/**
 * 抽奖活动配置
 * Class LuckdrawConfig
 * @package app\activity\controller
 */
class LuckdrawConfig extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'ActivityLuckdrawConfig';

    /**
     * 抽奖活动列表
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $this->title = '抽奖活动管理';
        return $this->_query($this->table)->order('id desc')->page();
    }

    /**
     *  添加活动
     * @return mixed
     */
    public function add()
    {
        $this->title = '添加抽奖活动';
        return $this->_form($this->table, 'form');
    }

    /**
     * 编辑活动
     * @return mixed
     */
    public function edit()
    {
        $this->title = '编辑抽奖活动';
        return $this->_form($this->table, 'form');
    }

    /**
     * 表单数据处理
     * @param array $vo
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    protected function _form_filter(&$vo)
    {
        if (empty($vo['id'])) $vo['id'] = time() . rand(10, 99);
        if ($this->request->isGet()) {
            $this->selectPrizes = Db::name('activity_luckdraw_config_record')->where(['cid' => $vo['id']])->select();
            $this->prizes = Db::name('ActivityLuckdrawPrize')->where(['is_deleted' => '0', 'status' => '1'])->select();
        } elseif ($this->request->isPost()) {
            list($post, $records) = [$this->request->post(), []];
            if (empty($post['logo'])) $this->error('奖品图片不能为空！');
            if (empty($post['prize_id']) || !is_array($post['prize_id'])) $this->error('请配置奖品信息！');
            $prizes = Db::name('ActivityLuckdrawPrize')->whereIn('id', $post['prize_id'])->select();
            foreach (array_keys($post['prize_id']) as $v) foreach ($prizes as $pz) {
                if (intval($pz['id']) === intval($post['prize_id'][$v])) array_push($records, [
                    'prize_id'    => $pz['id'], 'prize_logo' => $pz['logo'], 'cid' => $vo['id'],
                    'prize_title' => $pz['title'],
                    'prize_num'   => $post['prize_num'][$v],
                    'prize_rate'  => $post['prize_rate'][$v],
                    'prize_level' => $post['prize_level'][$v],
                ]);
            }
            Db::name('activity_luckdraw_config_record')->where(['cid' => $vo['id']])->delete();
            Db::name('activity_luckdraw_config_record')->insertAll($records);
        }
    }

    /**
     * 禁用活动
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 启用活动
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

    /**
     * 删除活动
     */
    public function del()
    {
        $this->_delete($this->table);
    }

}