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

    public function setContent(String $content)
    {
        $this->content = $content;
        return $this;
    }

    public function setContentByGates(\Closure $func)
    {
        $this->content = $func;
        return $this;
    }

    public function setTemplateID(String $templateID)
    {
        $this->template = $templateID;
        return $this;
    }

    public function setTemplateIDByGates(\Closure $func)
    {
        $this->template = $func;
        return $this;
    }

    public function setTemplateData(Array $data)
    {
        $this->templateData = $data;
        return $this;
    }

    public function setTemplateDataByGates(\Closure $func)
    {
        $this->templateData = $func;
        return $this;
    }

    public function setGateways(Array $gateways)
    {
        $this->gateways = $gateways;
        return $this;
    }

    public function setPhone($phone, $areaCode = false)
    {
        if ($areaCode) {
            $this->phone = new PhoneNumber($phone, $areaCode);
        } else {
            $this->phone = $phone;
        }
        return $this;
    }

    public function reset()
    {
        $this->phone = null;
        $this->content = null;
        $this->template = null;
        $this->templateData = null;
        $this->gateways = null;
    }

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
