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

namespace app\wechat\logic;

use library\File;
use think\Db;

/**
 * 微信素材管理
 * Class Media
 * @package app\wechat\logic
 */
class Media
{
    /**
     * 通过图文ID读取图文信息
     * @param integer $id 本地图文ID
     * @param array $where 额外的查询条件
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function news($id, $where = [])
    {
        $data = Db::name('WechatNews')->where(['id' => $id])->where($where)->find();
        list($data['articles'], $articleIds) = [[], explode(',', $data['article_id'])];
        $articles = Db::name('WechatNewsArticle')->whereIn('id', $articleIds)->select();
        foreach ($articleIds as $article_id) foreach ($articles as $article) {
            if (intval($article['id']) === intval($article_id)) array_push($data['articles'], $article);
            unset($article['create_by'], $article['create_at']);
        }
        return $data;
    }

    /**
     * 上传图片到微信服务器
     * @param string $local_url 图文地址
     * @return string
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function image($local_url)
    {
        $map = ['md5' => md5($local_url)];
        if (($media_url = Db::name('WechatNewsImage')->where($map)->value('media_url'))) {
            return $media_url;
        }
        $info = \We::WeChatMedia(Wechat::config())->uploadImg(self::getServerPath($local_url));
        data_save('WechatNewsImage', ['local_url' => $local_url, 'media_url' => $info['url'], 'md5' => $map['md5']], 'md5');
        return $info['url'];
    }

    /**
     * 上传图片永久素材，返回素材media_id
     * @param string $local_url 文件URL地址
     * @param string $type 文件类型
     * @param array $videoInfo 视频信息
     * @return string|null
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function upload($local_url, $type = 'image', $videoInfo = [])
    {
        $where = ['md5' => md5($local_url), 'appid' => Wechat::getAppid()];
        if (($mediaId = Db::name('WechatNewsMedia')->where($where)->value('media_id'))) return $mediaId;
        $result = \We::WeChatMedia(Wechat::config())->addMaterial(self::getServerPath($local_url), $type, $videoInfo);
        data_save('WechatNewsMedia', [
            'appid'    => Wechat::getAppid(), 'md5' => $where['md5'], 'type' => $type,
            'media_id' => $result['media_id'], 'local_url' => $local_url, 'media_url' => $result['url'],
        ], 'type', $where);
        return $result['media_id'];
    }

    /**
     * 文件位置处理
     * @param string $local
     * @return string
     */
    protected static function getServerPath($local)
    {
        if (file_exists($local)) return $local;
        return File::down($local)['file'];
    }
}