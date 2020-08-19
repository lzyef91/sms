<?php

namespace Nldou\SMS;

use Nldou\SMS\SMS;
use \Overtrue\EasySms\EasySms;
use \Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use \Illuminate\Contracts\Support\DeferrableProvider;

class ServiceProvider extends LaravelServiceProvider implements DeferrableProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/Config' => config_path()], 'nldou-sms-config');
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