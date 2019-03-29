<?php

namespace App\Channels;

use \Illuminate\Notifications\Notification;
use App\Channels\Messages\SMSMessage;

class SMSChannel
{
    /**
     * 发送短信通知
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSMS($notifiable);
        // 将通知发送给 $notifiable 实例
        if ($message instanceof SMSMessage) {
            if ($notifiable->phone) {
                return app('sms')->setPhone($notifiable->phone)->send($message);
            }
        }
    }
}