<?php

namespace Nldou\SMS;

use Nldou\SMS\SMS;
use \Overtrue\EasySms\EasySms;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/Config' => config_path()], 'nldou-sms-config');
            $this->publishes([__DIR__.'/Channels' => app_path('Channels')], 'nldou-sms-notification-channel');
        }
    }

    public function register()
    {
        $this->app->singleton(SMS::class, function () {
            $config = config('nldousms');
            $sms = new EasySms($config);
            return new SMS($sms);
        });
        $this->app->alias(SMS::class, 'sms');
    }

    public function provides()
    {
        return [SMS::class, 'sms'];
    }
}