<?php

Route::group(['prefix'=>'rbac','namespace'=> 'Eiixy\Rbac\Http\Controllers'],function (){
    Route::get('/', 'PermissionController@list');
    Route::post('/', 'PermissionController@store');
});
