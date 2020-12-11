<?php

return [
    /**
     * 用户表名或模型
     */
    'user' => 'users',

    /**
     * 权限中间件
     */
    'middleware' => [
        'api',
//        'jwt.auth',
//        'auth.api',
//        'auth:sanctum',
    ],

    /**
     * 角色组的权限/菜单缓存时间
     */
    'cache_ttl' => 43200,
];
