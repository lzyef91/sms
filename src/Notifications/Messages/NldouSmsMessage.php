<?php

namespace Nldou\SMS\Notifications\Messages;

use Overtrue\EasySms\Message;

class NldouSmsMessage extends Message
{
    // 可以自定义配置
    // 参照：https://github.com/overtrue/easy-sms 定义短信

    // // 自定义参数
    // protected $order;

    // // 定义本短信的网关使用策略，覆盖全局配置中的 `default.strategy`
    // protected $strategy = OrderStrategy::class;

    // // 定义本短信的适用平台，覆盖全局配置中的 `default.gateways`
    // protected $gateways = ['alidayu', 'yunpian', 'juhe'];

    // public function __construct($order)
    // {
    //     // 初始化自定义参数
    //     $this->order = $order;
    // }

    // // 定义直接使用内容发送平台的内容
    // public function getContent(GatewayInterface $gateway = null)
    // {
    //     return sprintf('您的订单:%s, 已经完成付款', $this->order->no);
    // }

    // // 定义使用模板发送方式平台所需要的模板 ID
    // public function getTemplate(GatewayInterface $gateway = null)
    // {
    //     return 'SMS_003';
    // }

    // // 模板参数
    // public function getData(GatewayInterface $gateway = null)
    // {
    //     return [
    //         'order_no' => $this->order->no
    //     ];
    // }
}
