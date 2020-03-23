<?php

namespace Eiixy\Rbac\Traits;

use Eiixy\Rbac\Models\Permission;
use Eiixy\Rbac\Models\RolePermissions;
use Eiixy\Rbac\Models\UserRole;

trait RbacUser
{
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
     * 获取菜单(最大三级)
     * @param $roles
     * @return array
     */
    public static function menus($roles)
    {
        $ids = RolePermissions::query()->whereIn('role_id', $roles)->pluck('permission_id');
        $menus = Permission::with(['children' => function ($query) use ($ids) {
            return $query->with(['children' => function ($query) use ($ids) {
                return $query->isMenu()->inRoles($ids);
            }])->isMenu()->inRoles($ids);
        }])
            ->where('pid', 0)
            ->inRoles($ids)
            ->isMenu()
            ->get()->toArray();
        return $menus;
    }

    /**
     * 获取权限
     * @param $roles
     * @return mixed
     */
    public static function permissions($roles)
    {
        $ids = RolePermissions::query()->whereIn('role_id', $roles)->pluck('permission_id');
        $permissions = Permission::query()->whereIn('id', $ids)->pluck('keyword')->map(function ($keyword) {
            if ($keyword) {
                return explode(',', $keyword);
            }
        })->collapse()->toArray();
        return $permissions;
    }

    /**
     * 判断是否拥有权限
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        $roles = static::roles($this->id);
        $permissions = static::permissions($roles);
        return in_array($permission, $permissions);
    }
}
