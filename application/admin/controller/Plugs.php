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

namespace app\admin\controller;

use app\admin\logic\File;
use library\Controller;

/**
 * 后台插件管理
 * Class Plugs
 * @package app\admin\controller
 */
class Plugs extends Controller
{

    /**
     * 文件上传
     * @return mixed
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function upfile()
    {
        $this->mode = $this->request->get('mode', 'one');
        $this->name = $this->request->get('name', 'file');
        $this->field = $this->request->get('field', 'file');
        $this->types = $this->request->get('type', 'jpg,png');
        $this->mimes = File::mine($this->types);
        if (in_array($this->request->get('uptype'), ['local', 'qiniu', 'oss'])) {
            $this->uptype = $this->request->get('uptype');
        } else $this->uptype = sysconf('storage_type');
        return $this->fetch();
    }

    /**
     * WebUpload文件上传
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function upload()
    {
        try {
            $file = $this->request->file($this->request->get('name', 'file'));
        } catch (\Exception $e) {
            return json(['code' => 'ERROR', 'msg' => lang($e->getMessage())]);
        }
        empty($file) && $this->error('文件上传异常，文件可能过大或未上传');
        if (!$file->checkExt(strtolower(sysconf('storage_local_exts')))) {
            return json(['code' => 'ERROR', 'msg' => '文件上传类型受限']);
        }
        // 文件本身处理
        $ext = strtolower(pathinfo($file->getInfo('name'), 4));
        $name = join('/', str_split($this->request->post('md5'), 16)) . '.' . (empty($ext) ? 'tmp' : $ext);
        // 文件上传Token验证
        if ($this->request->post('token') !== md5($name . session_id())) {
            return json(['code' => 'ERROR', 'msg' => '文件上传验证失败']);
        }
        $path = pathinfo(File::instance('local')->path($name));
        if ($file->move($path['dirname'], $path['basename'], true)) {
            if (is_array($info = File::instance('local')->info($name)) && isset($info['url'])) {
                return json(['data' => ['site_url' => $info['url']], 'code' => 'SUCCESS', 'msg' => '文件上传成功']);
            }
        }
        return json(['code' => 'ERROR', 'msg' => '文件上传失败']);
    }

    /**
     * Plupload插件上传文件
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function plupload()
    {
        try {
            $file = $this->request->file($this->request->get('name', 'file'));
        } catch (\Exception $e) {
            return json(['code' => 'ERROR', 'msg' => lang($e->getMessage())]);
        }
        empty($file) && $this->error('文件上传异常，文件可能过大或未上传');
        if (!$file->checkExt(strtolower(sysconf('storage_local_exts')))) {
            return json(['uploaded' => false, 'error' => ['message' => '文件上传类型受限']]);
        }
        $ext = strtolower(pathinfo($file->getInfo('name'), 4));
        $name = join('/', str_split(md5_file($file->getPathname()), 16)) . "." . (empty($ext) ? 'tmp' : $ext);
        $path = pathinfo(File::instance('local')->path($name));
        if ($file->move($path['dirname'], $path['basename'], true)) {
            if (is_array($info = File::instance('local')->info($name)) && isset($info['url'])) {
                return json(['uploaded' => true, 'filename' => $file->getInfo('name'), 'url' => $info['url']]);
            }
        }
        return json(['uploaded' => false, 'error' => ['message' => '文件处理失败，请稍候再试！']]);
    }

    /**
     * 文件状态检查
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function upstate()
    {
        $post = $this->request->post();
        $ext = strtolower(pathinfo($post['filename'], 4));
        $name = join('/', str_split($post['md5'], 16)) . '.' . (empty($ext) ? 'tmp' : $ext);
        // 检查文件是否已上传
        if (($site_url = File::url($name))) {
            $this->success('文件已存在可秒传！', ['site_url' => $site_url], 'IS_FOUND');
        }
        // 需要上传文件，生成上传配置参数
        $param = ['uptype' => $post['uptype'], 'file_url' => $name, 'server' => File::upload()];
        switch (strtolower($post['uptype'])) {
            case 'local':
                $param['token'] = md5($name . session_id());
                break;
            case 'qiniu':
                list($basic, $bucket, $access, $secret) = [
                    File::instance('qiniu')->base(), sysconf('storage_qiniu_bucket'),
                    sysconf('storage_qiniu_access_key'), sysconf('storage_qiniu_secret_key'),
                ];
                $data = str_replace(['+', '/'], ['-', '_'], base64_encode(json_encode([
                    "scope"      => "{$bucket}:{$name}", "deadline" => 3600 + time(),
                    "returnBody" => "{\"data\":{\"site_url\":\"{$basic}/$(key)\",\"file_url\":\"$(key)\"}, \"code\": \"SUCCESS\"}",
                ])));
                $param['token'] = "{$access}:" . str_replace(['+', '/'], ['-', '_'], base64_encode(hash_hmac('sha1', $data, $secret, true))) . ":{$data}";
                break;
            case 'oss':
                $param['site_url'] = File::instance('oss')->base($name);
                $param['OSSAccessKeyId'] = sysconf('storage_oss_keyid');
                $param['policy'] = base64_encode(json_encode([
                    'conditions' => [['content-length-range', 0, 1048576000]], 'expiration' => gmdate("Y-m-d\TH:i:s\Z", time() + 3600),
                ]));
                $param['signature'] = base64_encode(hash_hmac('sha1', $param['policy'], sysconf('storage_oss_secret'), true));
                break;
        }
        $this->success('文件未上传', $param, 'NOT_FOUND');
    }

    /**
     * 系统选择器图标
     * @return mixed
     */
    public function icon()
    {
        $this->title = '图标选择器';
        $this->field = $this->request->get('field', 'icon');
        return $this->fetch();
    }
}