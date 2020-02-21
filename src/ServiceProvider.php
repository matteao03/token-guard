<?php

namespace Matteao\TokenGuard;

use \Illuminate\Contracts\Support\DeferrableProvider;
use \Illuminate\Support\ServiceProvider;

class TokenServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * 在服务容器里注册
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * 获取由提供者提供的服务。
     *
     * @return array
     */
    public function provides()
    {
    }
}
