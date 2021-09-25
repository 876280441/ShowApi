<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Faker\Provider\Base;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OssController extends BaseController
{
    /*
     * 生成阿里云Oss
     */
    public function token()
    {
        //选择磁盘使用
        $disk = Storage::disk('oss');
        /**
         * 1. 前缀如：'images/'
         * 2. 回调服务器 url
         * 3. 回调自定义参数，oss 回传应用服务器时会带上
         * 4. 当前直传配置链接有效期
         */
        $config = $disk->signatureConfig($prefix = '/', $callBackUrl = '', $customData = [], $expire = 300);
        //将数据转换成数组
        $configArr = json_decode($config, true);
        return $this->response->array($configArr);
    }
}
