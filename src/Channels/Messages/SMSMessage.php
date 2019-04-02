<?php

namespace App\Channels\Messages;

use \Overtrue\EasySms\Message;
use \Overtrue\EasySms\Strategies\OrderStrategy;

class SMSMessage extends Message
{
    // 定义本短信的网关使用策略，覆盖全局配置中的 `default.strategy`
    protected $strategy = OrderStrategy::class;
    // 定义本短信的适用平台，覆盖全局配置中的 `default.gateways`
    protected $gateways = ['aliyun'];
}
