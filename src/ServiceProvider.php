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
        $source = realpath(__DIR__.'/Config/nldousms.php');
        $this->mergeConfigFrom($source, 'nldousms');
        $config = config('nldousms');
        $easysms = new EasySms($config);

        $this->app->singleton(SMS::class, function ($app) use ($easysms) {
            return new SMS($easysms);
        });
        $this->app->alias(SMS::class, 'sms');
    }

    public function provides()
    {
        return [SMS::class, 'sms'];
    }
}