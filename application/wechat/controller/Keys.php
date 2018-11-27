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

namespace app\wechat\controller;

use app\wechat\logic\Wechat;
use library\Controller;
use think\Db;


/**
 * Class Keys
 * @package app\wechat\controller
 */
class Keys extends Controller
{
    /**
     * 绑定数据表
     * @var string
     */
    protected $table = 'WechatKeys';

    /**
     * 显示关键字列表
     * @return array|string
     */
    public function index()
    {
        // 关键字二维码生成
        if ($this->request->get('action') === 'qrc') {
            try {
                $wechat = \We::WeChatQrcode(Wechat::config());
                $result = $wechat->create($this->request->get('keys', ''));
                $this->success('生成二维码成功！', "javascript:$.previewImage('{$wechat->url($result['ticket'])}')");
            } catch (\think\exception\HttpResponseException $exception) {
                throw  $exception;
            } catch (\Exception $e) {
                $this->error("生成二维码失败，请稍候再试！<br> {$e->getMessage()}");
            }
        }
        // 关键字列表显示
        $this->title = '微信关键字管理';
        $db = Db::name($this->table)->whereNotIn('keys', ['subscribe', 'default']);
        return $this->_page($db->order('sort asc,id desc'));
    }

    /**
     * 列表数据处理
     * @param array $data
     */
    protected function _index_page_filter(&$data)
    {
        try {
            $types = ['keys' => '关键字', 'image' => '图片', 'news' => '图文', 'music' => '音乐', 'text' => '文字', 'video' => '视频', 'voice' => '语音'];
            foreach ($data as &$vo) {
                $vo['qrc'] = url('@wechat/keys/index') . "?action=qrc&keys={$vo['keys']}";
                $vo['type'] = isset($types[$vo['type']]) ? $types[$vo['type']] : $vo['type'];
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * 添加关键字
     * @return string
     */
    public function add()
    {
        $this->title = '添加关键字规则';
        return $this->_form($this->table, 'form');
    }

    /**
     * 编辑关键字
     * @return string
     */
    public function edit()
    {
        $this->title = '编辑关键字规则';
        return $this->_form($this->table, 'form');
    }

    /**
     * 删除关键字
     */
    public function del()
    {
        $this->_delete($this->table);
    }

    /**
     * 禁用关键字
     */
    public function forbid()
    {
        $this->_save($this->table, ['status' => '0']);
    }

    /**
     * 启用关键字
     */
    public function resume()
    {
        $this->_save($this->table, ['status' => '1']);
    }

}