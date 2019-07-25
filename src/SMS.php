<?php

namespace Nldou\SMS;
use \Overtrue\EasySms\EasySms;
use \Overtrue\EasySms\PhoneNumber;
use \Overtrue\EasySms\Message;
use \Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use Nldou\SMS\Exceptions\NoGatewayAvailableException as GateException;
use Nldou\SMS\Exceptions\InvalidParamsException;

class SMS
{
    protected $content;
    protected $template;
    protected $templateData;
    protected $gateways;
    protected $phone;
    protected $sms;

    public function __construct(EasySms $sms)
    {
        $this->sms = $sms;
    }

    /**
     * 设置短信内容
     * @param string $content
     */
    public function setContent(String $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * 通过回调函数设置短信内容
     * 回调参数为当前网关 Overtrue\EasySms\GatewaysGateway实例 可以使用其中的方法
     * @param closure $func($gateway)
     */
    public function setContentByGates(\Closure $func)
    {
        $this->content = $func;
        return $this;
    }

    /**
     * 设置模板
     * @param string $templateID
     */
    public function setTemplateID(String $templateID)
    {
        $this->template = $templateID;
        return $this;
    }

    /**
     * 通过回调函数设置模板
     * 回调参数为当前网关 Overtrue\EasySms\GatewaysGateway实例 可以使用其中的方法
     * @param closure $func($gateway)
     */
    public function setTemplateIDByGates(\Closure $func)
    {
        $this->template = $func;
        return $this;
    }

    /**
     * 设置短信模板参数
     * @param array $data
     */
    public function setTemplateData(Array $data)
    {
        $this->templateData = $data;
        return $this;
    }

    /**
     * 通过回调函数设置短信模板参数
     * 回调参数为当前网关 Overtrue\EasySms\GatewaysGateway实例 可以使用其中的方法
     * @param closure $func($gateway)
     */
    public function setTemplateDataByGates(\Closure $func)
    {
        $this->templateData = $func;
        return $this;
    }

    /**
     * 设置网关
     * 按数组顺序进行调用 例 ['aliyun','yunpian'...]
     */
    public function setGateways(Array $gateways)
    {
        $this->gateways = $gateways;
        return $this;
    }

    /**
     * 设置手机号码
     * @param string $phone 手机号码
     * @param mixed $areaCode 国家代码
     */
    public function setPhone(String $phone, $areaCode = false)
    {
        if ($areaCode) {
            $this->phone = new PhoneNumber($phone, $areaCode);
        } else {
            $this->phone = $phone;
        }
        return $this;
    }

    private function reset()
    {
        $this->phone = null;
        $this->content = null;
        $this->template = null;
        $this->templateData = null;
        $this->gateways = null;
    }

    /**
     * 发送短信
     * @param mixed $msgCls \Overtrue\EasySms\Message实例 使用短信类发送
     */
    public function send(Message $msgCls = null)
    {
        if (!$this->phone) {
            throw new InvalidParamsException('phone num is empty');
        }
        // 自定义短信类
        if ($msgCls) {
            try {
                $res = $this->sms->send($this->phone, $msgCls);
                $this->reset();
                return $res;
            } catch (NoGatewayAvailableException $e) {
                throw new GateException($e->getMessage(), $e->getCode(), $e);
            }
        }
        // 配置短信
        $options = [];
        if ($this->content) {
            $options['content'] = $this->content;
        }
        if ($this->template) {
            $options['template'] = $this->template;
        }
        if ($this->templateData) {
            $options['data'] = $this->templateData;
        }
        if (empty($options)) {
            throw new InvalidParamsException('sms options is empty');
        }
        try {
            if ($this->gateways) {
                // 自定义网关
                $res = $this->sms->send($this->phone, $options, $this->gateways);
            } else {
                // 默认全局配置网关
                $res = $this->sms->send($this->phone, $options);
            }
            $this->reset();
            return $res;
        } catch (NoGatewayAvailableException $e) {
            throw new GateException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
