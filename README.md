# laravel-rbac
> 基于laravel的后台用户权限管理拓展包
1. 使用 composer 安装
    ```bash
    
    composer require eiixy/laravel-rbac
    
    ```

2. 发布配置文件
    ```bash
    # 这条命令会在 config 下增加一个 rbac.php 的配置文件
    php artisan vendor:publish --provider="Eiixy\Rbac\Providers\RbacServiceProvider"
    # 发布 jwt 配置文件
    php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
    # 生成密钥
    php artisan jwt:secret
    ```

3. 配置多 guard 来区分认证，修改 `config/auth.php` 文件
    ```base
    [
        ...
        'guards' => [
            'web' => [
                'driver' => 'session',
                'provider' => 'users',
            ],
    
            'api' => [
                'driver' => 'jwt',  // 默认是 token
                'provider' => 'users',
            ],
            // 新增admins 模块
            'admin' => [
                'driver' => 'jwt',
                'provider' => 'admins',
            ]
        ],
    
        'providers' => [
            'users' => [
                'driver' => 'eloquent',
                'model' => App\Models\User::class,
            ],
            'admins' => [
                'driver' => 'eloquent',
                'model' => App\Models\Admin::class,
            ]
        ],
        ...
    ];
    ```
3. 进行数据库迁移
    ```bash
    php artisan migrate
    ```
