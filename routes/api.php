<?php

Route::group(['prefix' => 'api/rbac', 'middleware' => ['api'], 'namespace' => 'Sczts\Rbac\Http\Controllers'], function () {
    Route::get('routes','CommonController@routes');
    // 权限管理
    Route::group(['prefix' => 'permission', 'middleware' => config('rbac.middleware')], function () {
        Route::get('/', 'PermissionController@list')->middleware('rbac.check:sys_permission.list');
        Route::get('/all', 'PermissionController@all')->middleware('rbac.check:sys_permission.all');
        Route::post('/', 'PermissionController@store')->middleware('rbac.check:sys_permission.add');
        Route::get('/{id}', 'PermissionController@show')->middleware('rbac.check:sys_permission.show');
        Route::put('/{id}', 'PermissionController@update')->middleware('rbac.check:sys_permission.update');
        Route::delete('/{id}', 'PermissionController@destroy')->middleware('rbac.check:sys_permission.delete');
    });
    // 角色管理
    Route::group(['prefix' => 'role', 'middleware' => config('rbac.middleware')], function () {
        Route::get('/', 'RoleController@list')->middleware('rbac.check:sys_role.list');
        Route::post('/', 'RoleController@store')->middleware('rbac.check:sys_role.add');
        Route::get('/{id}', 'RoleController@show')->middleware('rbac.check:sys_role.show');
        Route::put('/{id}', 'RoleController@update')->middleware('rbac.check:sys_role.update');
        Route::delete('/{id}', 'RoleController@destroy')->middleware('rbac.check:sys_role.delete');
    });
});
