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

use app\wechat\logic\Fans;
use app\wechat\logic\Media;
use app\wechat\logic\Wechat;
use library\Controller;
use think\Db;

/**
 * 公众号消息推送处理
 * Class Push
 * @package app\wechat\controller\api
 */
class Push extends Controller
{

    /**
     * 公众号APPID
     * @var string
     */
    protected $appid;

    /**
     * 微信用户OPENID
     * @var string
     */
    protected $openid;

    /**
     * 微信消息对象
     * @var array
     */
    protected $receive;

    /**
     * 微信实例对象
     * @var \WeChat\Receive
     */
    protected $wechat;

    /**
     * 强制客服消息回复
     * @var boolean
     */
    protected $forceCustom = false;

    /**
     * 获取网络出口IP
     * @return mixed
     */
    public function geoip()
    {
        return $this->request->ip();
    }

    /**
     * 消息推送处理接口
     * @return string
     */
    public function index()
    {
        try {
            $this->wechat = Wechat::WeChatReceive();
            if ($this->request->has('receive', 'post') && Wechat::getType() === 'thr') {
                $this->appid = $this->request->post('appid', '', null);
                $this->openid = $this->request->post('openid', '', null);
                $this->receive = $this->toLower(unserialize($this->request->post('receive', '', null)));
                if (empty($this->appid) || empty($this->openid) || empty($this->receive)) {
                    throw new \think\Exception('微信API实例缺失必要参数[appid,openid,receive]');
                }
                // $this->forceCustom = true;
            } else {
                $this->appid = Wechat::getAppid();
                $this->openid = $this->wechat->getOpenid();
                $this->receive = $this->toLower($this->wechat->getReceive());
            }
            p(PHP_EOL . '===== 消息接口获取的内容 =====');
            p($this->receive);
            // text, event, image, location
            if (method_exists($this, ($method = $this->receive['msgtype']))) {
                if (is_string(($result = $this->$method()))) {
                    p('===== 消息直接回复的内容 =====');
                    p($result);
                    return $result;
                }
                p('==== 异常消息 ======');
                p($result);
            }
            return 'success';
        } catch (\Exception $e) {
            p('===== 回复内容消息异常 =====');
            p(__METHOD__ . ":{$e->getLine()} [{$e->getCode()}] {$e->getMessage()}");
            \think\facade\Log::error(__METHOD__ . ":{$e->getLine()} [{$e->getCode()}] {$e->getMessage()}");
        }
        p('===== 无需要回复内容 =====');
        return 'success';
    }

    /**
     * 数组KEY全部转小写
     * @param array $data
     * @return array
     */
    private function toLower(array $data)
    {
        $data = array_change_key_case($data, CASE_LOWER);
        foreach ($data as $key => $vo) if (is_array($vo)) {
            $data[$key] = $this->toLower($vo);
        }
        return $data;
    }

    /**
     * 文件消息处理
     * @return boolean
     * @throws \WeChat\Exceptions\InvalidDecryptException
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    protected function text()
    {
        return $this->keys("wechat_keys#keys#{$this->receive['content']}", false, $this->forceCustom);
    }

    /**
     * 事件消息处理
     * @return boolean|string
     * @throws \WeChat\Exceptions\InvalidDecryptException
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    protected function event()
    {
        switch (strtolower($this->receive['event'])) {
            case 'subscribe':
                $this->updateFansinfo(true);
                if (isset($this->receive['eventkey']) && is_string($this->receive['eventkey'])) {
                    if (($key = preg_replace('/^qrscene_/i', '', $this->receive['eventkey']))) {
                        return $this->keys("wechat_keys#keys#{$key}", false, true);
                    }
                }
                return $this->keys('wechat_keys#keys#subscribe', true, $this->forceCustom);
            case 'unsubscribe':
                return $this->updateFansinfo(false);
            case 'click':
                return $this->keys("wechat_keys#keys#{$this->receive['eventkey']}", false, $this->forceCustom);
            case 'scancode_push':
            case 'scancode_waitmsg':
                if (empty($this->receive['scancodeinfo'])) return false;
                if (empty($this->receive['scancodeinfo']['scanresult'])) return false;
                return $this->keys("wechat_keys#keys#{$this->receive['scancodeinfo']['scanresult']}", false, $this->forceCustom);
            case 'scan':
                if (empty($this->receive['eventkey'])) return false;
                return $this->keys("wechat_keys#keys#{$this->receive['eventkey']}", false, $this->forceCustom);
            default:
                return false;
        }
    }

    /**
     * 关键字处理
     * @param string $rule 关键字规则
     * @param boolean $isLast 重复回复消息处理
     * @param boolean $isCustom 是否使用客服消息发送
     * @return boolean|string
     * @throws \WeChat\Exceptions\InvalidDecryptException
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    private function keys($rule, $isLast = false, $isCustom = false)
    {
        list($table, $field, $value) = explode('#', $rule . '##');
        $data = Db::name($table)->where([$field => $value])->find();
        if (empty($data['type']) || (array_key_exists('status', $data) && empty($data['status']))) {
            return $isLast ? false : $this->keys('wechat_keys#keys#default', true, $isCustom);
        }
        switch (strtolower($data['type'])) {
            case 'keys':
                $content = empty($data['content']) ? $data['name'] : $data['content'];
                return $this->keys("wechat_keys#keys#{$content}", $isLast, $isCustom);
            case 'text':
                return $this->sendMessage('text', ['content' => $data['content']], $isCustom);
            case 'customservice':
                return $this->sendMessage('customservice', ['content' => $data['content']], false);
            case 'voice':
                if (empty($data['voice_url']) || !($media_id = Media::upload($data['voice_url'], 'voice'))) return false;
                return $this->sendMessage('voice', ['media_id' => $media_id], $isCustom);
            case 'image':
                if (empty($data['image_url']) || !($media_id = Media::upload($data['image_url'], 'image'))) return false;
                return $this->sendMessage('image', ['media_id' => $media_id], $isCustom);
            case 'news':
                list($news, $articles) = [Media::news($data['news_id']), []];
                if (empty($news['articles'])) return false;
                foreach ($news['articles'] as $vo) array_push($articles, [
                    'url'   => url("@wechat/api.review/view", '', false, true) . "?id={$vo['id']}",
                    'title' => $vo['title'], 'picurl' => $vo['local_url'], 'description' => $vo['digest'],
                ]);
                return $this->sendMessage('news', ['articles' => $articles], $isCustom);
            case 'music':
                if (empty($data['music_url']) || empty($data['music_title']) || empty($data['music_desc'])) return false;
                return $this->sendMessage('music', [
                    'thumb_media_id' => empty($data['music_image']) ? '' : Media::upload($data['music_image'], 'image'),
                    'description'    => $data['music_desc'], 'title' => $data['music_title'],
                    'hqmusicurl'     => $data['music_url'], 'musicurl' => $data['music_url'],
                ], $isCustom);
            case 'video':
                if (empty($data['video_url']) || empty($data['video_desc']) || empty($data['video_title'])) return false;
                $videoData = ['title' => $data['video_title'], 'introduction' => $data['video_desc']];
                if (!($media_id = Media::upload($data['video_url'], 'video', $videoData))) return false;
                return $this->sendMessage('video', ['media_id' => $media_id, 'title' => $data['video_title'], 'description' => $data['video_desc']], $isCustom);
            default:
                return false;
        }
    }

    /**
     * 发送消息到公众号
     * @param string $type 消息类型（text|image|voice|video|music|news|mpnews|wxcard）
     * @param array $data 消息内容数据对象
     * @param boolean $isCustom 是否使用客服消息发送
     * @return array|boolean
     * @throws \WeChat\Exceptions\InvalidDecryptException
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    private function sendMessage($type, $data, $isCustom = false)
    {
        if ($isCustom) {
            p('===== 消息回复客服消息的内容 =====');
            $info = ['touser' => $this->openid, 'msgtype' => $type, "{$type}" => $data];
            p($info);
            Wechat::WeChatCustom()->send($info);
        } else switch (strtolower($type)) {
            case 'text': // 发送文本消息
                $reply = [
                    'MsgType'      => 'text',
                    'CreateTime'   => time(),
                    'Content'      => $data['content'],
                    'ToUserName'   => $this->openid,
                    'FromUserName' => $this->receive,
                ];
                p($reply);
                return $this->wechat->reply($reply, true);
            //exit;
            //return $this->wechat->text($data['content'])->reply([], true);
            case 'image': // 发送图片消息
                return $this->wechat->image($data['media_id'])->reply([], true);
            case 'voice': // 发送语言消息
                return $this->wechat->voice($data['media_id'])->reply([], true);
            case 'video': // 发送视频消息
                return $this->wechat->video($data['media_id'], $data['title'], $data['description'])->reply([], true);
            case 'music': // 发送音乐消息
                return $this->wechat->music($data['title'], $data['description'], $data['musicurl'], $data['hqmusicurl'], $data['thumb_media_id'])->reply([], true);
            case 'customservice': // 转交客服消息
                if ($data['content']) $this->sendMessage('text', $data, true);
                return $this->wechat->transferCustomerService()->reply([], true);
            case 'news': // 发送图文消息
                $articles = [];
                foreach ($data['articles'] as $article) array_push($articles, ['PicUrl' => $article['picurl'], 'Title' => $article['title'], 'Description' => $article['description'], 'Url' => $article['url']]);
                return $this->wechat->news($articles)->reply([], true);
            default:
                return 'success';
        }
    }

    /**
     * 同步粉丝状态
     * @param boolean $subscribe 关注状态
     * @return boolean
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    private function updateFansinfo($subscribe = true)
    {
        if ($subscribe) {
            try {
                $user = Wechat::WeChatUser()->getUserInfo($this->openid);
                return Fans::set(array_merge($user, ['subscribe' => '1']));
            } catch (\Exception $e) {
                \think\facade\Log::error(__METHOD__ . " {$this->openid} 粉丝信息获取失败，{$e->getMessage()}");
                return false;
            }
        }
        $user = ['subscribe' => '0', 'openid' => $this->openid, 'appid' => $this->appid];
        return data_save('WechatFans', $user, 'openid', ['appid' => $this->appid]);
    }

}