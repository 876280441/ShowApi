<?php

namespace App\Providers;

use App\Facades\Express\Express;
use App\Models\Category;
use App\Observers\CategoryObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * 注册门面
     *
     * @return void
     */
    public function register()
    {
        //注册自定义门面
        $this->app->singleton('Express', function () {
            return new Express();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //把观察者Category模型事件注册进来
        Category::observe(CategoryObserver::class);
    }
}
