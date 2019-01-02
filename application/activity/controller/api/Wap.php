<?php

namespace app\activity\controller\api;

use app\wechat\logic\Wechat;
use library\Controller;
use think\Db;

/**
 * 首页活动管理
 * Class Wap
 * @package app\activity\controller\api
 */
class Wap extends Controller
{

    /**
     * Wap constructor.
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function __construct()
    {
        parent::__construct();
        $this->openid = Wechat::getWebOauthInfo($this->request->url(true), 0, true)['openid'];
    }

    /**
     * 进入活动列表
     * @param string $code
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index($code)
    {
        $this->init($code);
        $this->records = Db::name('activity_luckdraw_member_record')->where(['cid' => $this->vo['id']])->order('id desc')->select();
        return $this->fetch();
    }

    /**
     * 用户信息录入
     * @param string $code
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function info($code)
    {
        $this->init($code);
        list($username, $phone) = [input('username'), input('phone')];
        if (empty($username)) $this->error('请输入您的姓名！');
        if (empty($phone) || !preg_match('/^1\d{10}$/i', $phone)) $this->error('请输入正确的手机号！');
        $data = ['openid' => $this->openid, 'username' => $username, 'phone' => $phone];
        if (data_save('activity_luckdraw_member', $data, 'openid')) {
            $this->success('信息录入成功，可以进行抽奖了！');
        }
        $this->error('信息录入失败，请稍候再试！');
    }

    /**
     * 进行抽奖
     * @param string $code
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function prize($code)
    {
        $this->init($code);
        $result = Db::name('activity_luckdraw_member_record')->where(['cid' => $this->vo['id'], 'mid' => $this->member['id']])->find();
        if ($result) $this->error('已经参与抽奖，不能再抽奖了！');
        $result = Db::name('activity_luckdraw_member_record')->field('count(1) count,prize_id')->where(['cid' => $this->vo['id']])->group('prize_id')->select();
        $config = Db::name('activity_luckdraw_config_record')->field('sum(prize_num) prize_num,sum(prize_rate) prize_rate,prize_level,prize_id')->where(['cid' => $this->vo['id']])->group('prize_id')->select();
        list($tempRate, $prize, $tempNumber) = [0, [], rand(1, 1000000) / 10000];
        // 移除无奖品数量的选项
        foreach ($config as $key => $item) foreach ($result as $record) if ($item['prize_num'] <= $record['count']) unset($config[$key]);
        // 获取奖品信息
        foreach ($config as $item) if ($tempNumber < ($tempRate += $item['prize_rate'])) {
            $prize = $item;
            break;
        }
        if (empty($prize)) {
            $result = Db::name('activity_luckdraw_member_record')->insert([
                'mid'      => $this->member['id'],
                'username' => $this->member['username'],
                'cid'      => $this->vo['id'],
                'prize_id' => 0,
            ]);
            if ($result !== false) $this->success('抱歉没有抽到奖品哦！');
        } else {
            $_prize = Db::name('activity_luckdraw_prize')->where(['id' => $prize['prize_id']])->find();
            $result = Db::name('activity_luckdraw_member_record')->insert([
                'mid'           => $this->member['id'],
                'username'      => $this->member['username'],
                'cid'           => $this->vo['id'],
                'prize_id'      => $_prize['id'],
                'prize_logo'    => $_prize['logo'],
                'prize_level'   => $prize['prize_level'],
                'prize_title'   => $_prize['title'],
                'prize_content' => $_prize['content'],
            ]);
            if ($result !== false) $this->success('奖品抽取成功！');
        }
        $this->error('抽奖失败，请稍候再试！');
    }

    /**
     * 奖品核销
     * @param string $code
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function used($code)
    {
        $uncode = input('uncode');
        if (empty($uncode)) $this->error('奖品核销码不能为空！');
        $this->init($code);
        if ($this->vo['code'] != $uncode) $this->error('奖品核销码错误，请重新输入！');
        if (!empty($this->vo['uncode_state'])) $this->error('该奖品已经核销！');
        $resutl = Db::name('activity_luckdraw_member_record')->where(['id' => $this->record['id']])->update([
            'uncode'       => $uncode,
            'uncode_state' => '1',
            'uncode_at'    => date('Y-m-d H:i:s'),
        ]);
        if ($resutl !== false) $this->success('奖品核销成功！');
        $this->error('奖品核销失败，请稍候再试！');
    }


    /**
     * 抽奖信息初始化
     * @param string $code
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function init($code)
    {
        $this->vo = Db::name('activity_luckdraw_config')->where(['id' => $code])->find();
        if (empty($this->vo)) $this->error('活动不存在，请通过邀请二维码进入！');
        $this->rules = explode("\n", $this->vo['content']);
//        $this->openid = 'testopenid';
        $this->member = Db::name('activity_luckdraw_member')->where(['openid' => $this->openid])->find();
        $this->record = Db::name('activity_luckdraw_member_record')->where(['mid' => $this->member['id'], 'cid' => $this->vo['id']])->find();
    }
}