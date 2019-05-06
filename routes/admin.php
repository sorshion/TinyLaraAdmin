<?php
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| 后台公共路由部分
|
*/
Route::namespace('Admin')->prefix('admin')->group(function () {
    // 登录、注销
    Route::get('login', 'LoginController@showLoginForm')->name('admin.loginForm');
    Route::post('login', 'LoginController@login')->name('admin.login');
    Route::get('logout', 'LoginController@logout')->name('admin.logout');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| 后台需要授权的路由 admins
|
*/
Route::namespace('Admin')->prefix('admin')->middleware('auth')->group(function () {
    // 后台布局
    Route::get('/', 'IndexController@layout')->name('admin.layout');
    // 后台首页
    Route::get('/index', 'IndexController@index')->name('admin.index');
    // 图标
    Route::get('icons', 'IndexController@icons')->name('admin.icons');
});

// 系统管理
Route::namespace('Admin')->prefix('admin')->middleware(['auth', 'permission:system.manage'])->group(function () {
    // 数据表格接口
    Route::get('data', 'IndexController@data')->name('admin.data')->middleware('permission:system.role|system.user|system.permission');

    // 用户管理
    Route::prefix('user')->middleware(['permission:system.user'])->group(function () {
        Route::get('index', 'UserController@index')->name('admin.user');
        // 添加
        Route::get('create', 'UserController@create')->name('admin.user.create')->middleware('permission:system.user.create');
        Route::post('store', 'UserController@store')->name('admin.user.store')->middleware('permission:system.user.create');
        // 编辑
        Route::get('{id}/edit', 'UserController@edit')->name('admin.user.edit')->middleware('permission:system.user.edit');
        Route::put('{id}/update', 'UserController@update')->name('admin.user.update')->middleware('permission:system.user.edit');
        // 删除
        Route::delete('destroy', 'UserController@destroy')->name('admin.user.destroy')->middleware('permission:system.user.destroy');
        // 分配角色
        Route::get('{id}/role', 'UserController@role')->name('admin.user.role')->middleware('permission:system.user.role');
        Route::put('{id}/assignRole', 'UserController@assignRole')->name('admin.user.assignRole')->middleware('permission:system.user.role');
        // 分配权限
        Route::get('{id}/permission', 'UserController@permission')->name('admin.user.permission')->middleware('permission:system.user.permission');
        Route::put('{id}/assignPermission', 'UserController@assignPermission')->name('admin.user.assignPermission')->middleware('permission:system.user.permission');
    });

    // 角色管理
    Route::prefix('role')->middleware(['permission:system.role'])->group(function () {
        Route::get('index', 'RoleController@index')->name('admin.role');
        // 添加
        Route::get('create', 'RoleController@create')->name('admin.role.create')->middleware('permission:system.role.create');
        Route::post('store', 'RoleController@store')->name('admin.role.store')->middleware('permission:system.role.create');
        // 编辑
        Route::get('{id}/edit', 'RoleController@edit')->name('admin.role.edit')->middleware('permission:system.role.edit');
        Route::put('{id}/update', 'RoleController@update')->name('admin.role.update')->middleware('permission:system.role.edit');
        // 删除
        Route::delete('destroy', 'RoleController@destroy')->name('admin.role.destroy')->middleware('permission:system.role.destroy');
        // 分配权限
        Route::get('{id}/permission', 'RoleController@permission')->name('admin.role.permission')->middleware('permission:system.role.permission');
        Route::put('{id}/assignPermission', 'RoleController@assignPermission')->name('admin.role.assignPermission')->middleware('permission:system.role.permission');
    });

    // 权限管理
    Route::prefix('permission')->middleware(['permission:system.permission'])->group(function () {
        Route::get('index', 'PermissionController@index')->name('admin.permission');
        // 添加
        Route::get('create', 'PermissionController@create')->name('admin.permission.create')->middleware('permission:system.permission.create');
        Route::post('store', 'PermissionController@store')->name('admin.permission.store')->middleware('permission:system.permission.create');
        // 编辑
        Route::get('{id}/edit', 'PermissionController@edit')->name('admin.permission.edit')->middleware('permission:system.permission.edit');
        Route::put('{id}/update', 'PermissionController@update')->name('admin.permission.update')->middleware('permission:system.permission.edit');
        // 删除
        Route::delete('destroy', 'PermissionController@destroy')->name('admin.permission.destroy')->middleware('permission:system.permission.destroy');
    });
});
