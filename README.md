# laravel-rbac
> 基于laravel的后台权限管理拓展包，精确到接口的权限管理
1. 使用 composer 安装
    ```bash
    composer require sczts/laravel-rbac
    ```

2. 发布配置文件
    ```bash
    # 这条命令会在 config 下增加一个 rbac.php 的配置文件
    php artisan vendor:publish --provider="Sczts\Rbac\Providers\RbacServiceProvider"
    ```

3. 修改配置文件 `config/rbac.php` 填写实际的用户表名/模型，配置对应使用的auth中间件
    ```base=
            'user' => 'users',
            'middleware' => [
                'api',
                // 'jwt.auth',
                // 'auth.api',
                // 'auth:sanctum',
            ],
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
7. 在路由中添加验证中间件 `rbac.check:{keyword}`
    ```php
    // 示例代码：
    Route::group(['prefix' => 'role', 'middleware' => ['api', 'auth:sanctum']], function () {
        Route::get('/', 'RoleController@list')->middleware('rbac.check:sys_role.list');
        Route::post('/', 'RoleController@store')->middleware('rbac.check:sys_role.add');
        Route::get('/{id}', 'RoleController@show')->middleware('rbac.check:sys_role.show');
        Route::put('/{id}', 'RoleController@update')->middleware('rbac.check:sys_role.update');
        Route::delete('/{id}', 'RoleController@destroy')->middleware('rbac.check:sys_role.delete');
    });
    ```
    

