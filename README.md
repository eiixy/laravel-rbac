# laravel-rbac
> 基于laravel的后台权限管理拓展包
1. 使用 composer 安装
    ```bash
    composer require sczts/laravel-rbac
    ```

2. 发布配置文件
    ```bash
    # 这条命令会在 config 下增加一个 rbac.php 的配置文件
    php artisan vendor:publish --provider="Sczts\Rbac\Providers\RbacServiceProvider"
    ```

3. 修改配置文件 `config/rbac.php` 
    ```base
            /**
             * 用户表名或模型
             */
            'user' => 'users',
        
            /**
             * 权限中间件
             */
            'middleware' => [
                // 'jwt.auth',
                // 'auth.api',
                // 'auth:sanctum',
            ],
        
            /**
             * 角色组的权限/菜单缓存时间
             */
            'cache_ttl' => 43200,
    ```
    
4. 进行数据库迁移
    ```bash
    php artisan migrate
    ```
 
5. 填充默认角色与权限信息（可选）
    ```bash
    php artisan db:seed --class=RbacPermissionSeeder
    php artisan db:seed --class=RbacRoleSeeder
    ```
    
6. 给User模型添加 trait `UserPermission`
    ```php
    use Sczts\Rbac\Traits\UserPermission;
    
    class User extends Authenticatable
    {
        use Notifiable, UserPermission;
    //......
    ```
    

