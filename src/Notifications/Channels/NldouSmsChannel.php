<?php

namespace Nldou\SMS\Notifications\Channels;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification;
use Nldou\SMS\Exceptions\CoundNotSendNotificationException;

class NldouSmsChannel
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
        if (method_exists($notifiable, 'routeNotificationForNldouSms')) {
            $to = $notifiable->routeNotificationForNldouSms($notification);
        } elseif ($notifiable instanceof AnonymousNotifiable) {
            $to = $notifiable->routes[__CLASS__];
        } else {
            throw new CoundNotSendNotificationException('UnableToObtainPhoneError');
        }

        $message = $notification->toNldouSms($notifiable);
        try {
            app('sms')->setPhone($to)->send($message);
        } catch (\Exception $e) {
            throw CoundNotSendNotificationException::serviceRespondedWithAnError($e);
        }
    }
}