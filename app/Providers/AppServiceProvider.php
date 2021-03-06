<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 兼容mysql低版本
        Schema::defaultStringLength(191);
        // 左侧菜单
        view()->composer('admin.layout', function($view) {
            $menus = \App\Models\Permission::with([
                'childs' => function($query){$query->with('icon');}
                ,'icon'])->where('parent_id',0)->orderBy('sort', 'desc')->get();
            $view->with('menus',$menus);
        });
    }
}
