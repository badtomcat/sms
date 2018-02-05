<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/22
 * Time: 13:30
 */

namespace Badtomcat\Sms\Ali;


class Send
{
    protected $accessKeyId;
    protected $accessKeySecret;

    protected $params = array();
    public $msg;
    public function __construct($accessKeyId,$accessKeySecret)
    {
        $this->accessKeyId = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;

    }


    public function debug()
    {
        ini_set("display_errors", "on"); // 显示错误提示，仅用于测试时排查问题
        set_time_limit(0); // 防止脚本超时，仅用于测试使用，生产环境请按实际情况设置
        header("Content-Type: text/plain; charset=utf-8"); // 输出为utf-8的文本格式，仅用于测试
    }

    /**
     * PhoneNumbers = 17000000000
     * SignName = 短信签名
     * TemplateCode = SMS_0000001
     * TemplateParam = [
     *      "code" => "12345"
     *      "product" => "阿里通信"
     * ]
     * SmsUpExtendCode   1234567   上行短信扩展码
     * @param array $params
     */
    public function setParams(array $params = [])
    {
        $this->params = $params;
    }

    /**
     * @param $no
     * @return $this
     */
    public function setPhoneNumber($no)
    {
        $this->params["PhoneNumbers"] = $no;
        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setSignName($name)
    {
        $this->params["SignName"] = $name;
        return $this;
    }

    /**
     * @param $code
     * @return $this
     */
    public function setTemplateCode($code)
    {
        $this->params["TemplateCode"] = $code;
        return $this;
    }

    /**
     * @param array $param
     * @return $this
     */
    public function setTemplateParam(array $param)
    {
        $this->params["TemplateParam"] = $param;
        return $this;
    }

    /**
     * @param $code
     * @return $this
     */
    public function setSmsUpExtendCode($code)
    {
        $this->params["SmsUpExtendCode"] = $code;
        return $this;
    }

    //[
    //  [Message] => OK
    //  [RequestId] => F169ACDD-FED8-4874-983C-7B2248498ED2
    //  [BizId] => 610706717816178725^0
    //  [Code] => OK
    //]
    /**
     * @param $PhoneNumbers
     * @return bool
     */
    public function send()
    {
//        $params = array ();

        // *** 需用户填写部分 ***

        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId = $this->accessKeyId;
        $accessKeySecret = $this->accessKeySecret;

        $params = $this->params;
        // fixme 必填: 短信接收号码


        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        );
        $this->msg = $content['Message'];
        return $content['Code'] == "OK";
    }
}