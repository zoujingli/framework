<?php

// +----------------------------------------------------------------------
// | Library for ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://library.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github 仓库地址 ：https://github.com/zoujingli/ThinkLibrary
// +----------------------------------------------------------------------

namespace library\tools\express;

use library\tools\Http;

/**
 * 德邦快递查询
 * Class Deppon
 * @package library\tools\express
 */
class Deppon
{

    /**
     * 公司编号
     * @var string
     */
    protected $company = 'EWBGZCCXXKJYXGS';

    /**
     * APPKEY
     * @var string
     */
    protected $appkey = 'f3ec81bd9887f63254a41801a2e4db8a';

    /**
     * 接口请求地址
     * @var string
     */
    protected $queryUrl = 'http://dpsanbox.deppon.com/sandbox-web/standard-order/newTraceQuery.action';

    /**
     * 物流查询接口
     * @param string $number
     */
    public function query($number)
    {
        include '../Http.php';
        $result = Http::post($this->queryUrl, $this->buildQueryData($number));
        var_dump($result);
    }

    /**
     * 生成查询数据
     * @param string $number
     * @return array
     */
    private function buildQueryData($number)
    {
        $time = time() . rand(100, 999);
        $json = json_encode(['mailNo' => $number]);
        return [
            'params'      => $json,
            'digest'      => base64_encode(md5($json . $this->appkey . $time)),
            'timestamp'   => $time,
            'companyCode' => $this->company,
        ];
    }

}

$q = new Deppon();
$q->query('7810449060');