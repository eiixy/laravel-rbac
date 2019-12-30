<?php


namespace Eiixy\Rbac\Models;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    const STATUS_NORMAL = 0;        // 正常
    const STATUS_FORBIDDEN = 1;     // 禁用

    const TYPE_ADMIN = 0;           // 管理员
    const TYPE_SUPER_ADMIN = 1;     // 超级管理员

    protected $table = 'rbac_users';
    protected $guarded = [];


    /**
     * 获取会储存到 jwt 声明中的标识
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * 返回包含要添加到 jwt 声明中的自定义键值对数组
     * @return array
     */
    public function getJWTCustomClaims()
    {

        return ['role' => 'admin'];
    }

    public static function roles($user_id)
    {
        return UserRole::query()->where('user_id', $user_id)->pluck('role_id')->toArray();
    }

    public function permission()
    {
        $roles = static::roles($this->id);
        return [
            'permissions' => $this->permissions($roles),
            'menus' => $this->menus($roles),
        ];
    }

    /**
     * 获取菜单
     * @param $roles
     * @return array
     */
    public function menus($roles)
    {
        $ids = RolePermissions::query()->whereIn('role_id', $roles)->pluck('permission_id');
        $menus = Permission::with(['children' => function ($query) {
            return $query->with(['children' => function ($query) {
                return $query->menu();
            }])->menu();
        }])
            ->where('pid', 0)
            ->whereIn('id', $ids)
            ->menu()
            ->get()->toArray();
        return $menus;
    }

    /**
     * 获取权限
     * @param $roles
     * @return mixed
     */
    public function permissions($roles)
    {
//        $key = implode('-', $roles);
//        return Cache::remember('roles/' . $key . ':permission', 60, function () use ($roles) {
            $ids = RolePermissions::query()->whereIn('role_id', $roles)->pluck('permission_id');
            $permissions = Permission::query()->whereIn('id', $ids)->pluck('keyword')->map(function ($keyword) {
                if ($keyword) {
                    return explode(',', $keyword);
                }
            })->collapse()->toArray();
            return $permissions;
//        });
    }

    public function hasPermission($permission)
    {
        $roles = static::roles($this->id);
        $permissions = static::permissions($roles);
        return in_array($permission, $permissions);
    }
}
