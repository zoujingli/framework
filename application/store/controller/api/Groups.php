<?php

namespace app\store\controller\api;

use library\Controller;
use think\Db;

/**
 * Class Groups
 * @package app\store\controller\api
 */
class Groups extends Controller
{
    /**
     * 获取团购信息列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function gets()
    {
        $where = ['status' => '1', 'is_deleted' => '0'];
        $groups = Db::name('StoreGroups')->where($where)->order('sort asc,id desc')->select();
        $groupsList = Db::name('StoreGroupsList')->whereIn('gid', array_unique(array_column($groups, 'id')))->select();
        $groupsCard = Db::name('StoreCard')->where($where)->whereIn('id', array_unique(array_column($groupsList, 'cid')))->select();
        foreach ($groupsList as &$g) foreach ($groupsCard as $c) if ($g['cid'] === $c['id']) $g = array_merge($g, $c);
        foreach ($groups as &$g) {
            list($g['cards'], $g['image']) = [[], explode('|', $g['image'])];
            unset($g['sort'], $g['content'], $g['create_at'], $g['is_deleted']);
            foreach ($groupsList as $gg) if ($g['id'] === $gg['gid']) $g['cards'][] = $gg;
        }
        $this->success('获取团购信息列表成功!', ['list' => $groups]);
    }

    /**
     * 获取团购信息
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get()
    {
        $gid = input('gid', '0');
        $where = ['status' => '1', 'is_deleted' => '0'];
        $groups = Db::name('StoreGroups')->where($where)->where(['id' => $gid])->find();
        if (empty($groups)) $this->error('获取团购信息失败，请稍候再试！');
        $groupsList = Db::name('StoreGroupsList')->where(['gid' => $gid])->select();
        $groupsCard = Db::name('StoreCard')->where($where)->whereIn('id', array_unique(array_column($groupsList, 'cid')))->select();
        foreach ($groupsList as &$g) foreach ($groupsCard as $c) if ($g['cid'] === $c['id']) $g = array_merge($g, $c);
        $groups['cards'] = $groupsCard;
        unset($groups['sort'], $groups['content'], $groups['create_at'], $groups['is_deleted']);
        $this->success('获取团购信息成功！', $groups);
    }

}