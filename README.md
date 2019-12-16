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
    ```

3. 进行数据库迁移
    ```bash
    php artisan migrate
    ```
