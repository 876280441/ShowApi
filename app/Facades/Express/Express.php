<?php


namespace App\Facades\Express;


use Illuminate\Support\Facades\Http;

class Express
{
    //商户id
    protected $EBusinessID;
    //API KEY
    protected $AppKey;
    //模式
    protected $mode;

    //构造方法
    public function __construct()
    {
        $config = config('express');
        $this->EBusinessID = $config['EBusinessID'];
        $this->AppKey = $config['AppKey'];
        $this->mode = $config['mode'] ?: 'product';
    }

    /*
     * 即时查询快递
     * @param ShipperCode 快递公司
     * @param LogisticCode 快递单号
     */
    public function track($ShipperCode, $LogisticCode)
    {
        //准备请求参数
        // 组装应用级参数
        $requestData = "{" .
            "'CustomerName': ''," .
            "'OrderCode': ''," .
            "'ShipperCode': '{$ShipperCode}'," .
            "'LogisticCode': '{$LogisticCode}'," .
            "}";

        //发送请求
        $result = Http::asForm()->post($this->url('track'), $this->formatReqData($requestData, '1002'));
        return $this->formatResData($result);
    }

    /*
     *格式化响应参数
     */
    protected function formatResData($result)
    {
        $result = json_decode($result, true);
        //API服务器报错
        if ($result['Success'] == false) {
            return $result['ResponseData'];
        }
        $result2 = json_decode($result['ResponseData'], true);
        //请求成功，但是未请求到数据，请求的参数有问题
        if ($result2['Success'] == false) {
            return $result2['Reason'];
        }
        return $result2;
    }

    /*
     * 格式化请求参数
     * @param requestData 请求参数
     * @param RequestType 查询类型
     * @param DataType 数据类型
     */
    protected function formatReqData($requestData, $RequestType = 1002, $DataType = 2)
    {
        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => $RequestType, //免费即时查询接口指令1002/在途监控即时查询接口指令8001/地图版即时查询接口指令8003
            'RequestData' => urlencode($requestData),
            'DataType' => $DataType,
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        return $datas;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param ApiKey ApiKey
     * @return DataSign签名
     */
    protected function encrypt($data)
    {
        return urlencode(base64_encode(md5($data . $this->AppKey)));
    }

    /*
     * 返回api url
     */
    protected function url($type)
    {
        $url = [
            'track' => [
                //正式环境
                'product' => '：https://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx',
                //沙箱调试
                'dev' => 'http://www.kdniao.com/UserCenter/v2/SandBox/SandboxHandler.ashx?action=CommonExcuteInterface'
            ]
        ];
        return $url[$type][$this->mode];
    }
}
