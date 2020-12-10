<?php

namespace Sczts\Rbac\Traits;

use Sczts\Rbac\Models\Permission;
use Sczts\Rbac\Models\Role;
use Sczts\Rbac\Models\RolePermissions;
use Sczts\Rbac\Models\UserRole;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

trait UserPermission
{
    /**
     * 角色权限缓存时间
     * @return int
     */
    private function ttl(){
        return 43200;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, UserRole::class, 'user_id', 'role_id');
    }

    public function permissions()
    {
        $roles = $this->roles->pluck('id')->toArray();
        return [
            'keywords' => $this->permissionKeywords($roles),
            'menus' => $this->permissionMenus($roles),
        ];
    }

    /**
     * 获取角色下包含的页面菜单权限
     */
    public function permissionMenus($roles)
    {
        sort($roles);
        $cache_key = 'role_permission_menus:' . implode('_', $roles);
        
        return Cache::remember($cache_key, $this->ttl(), function () use ($roles, $cache_key) {
            foreach ($roles as $role) {
                Redis::sadd('role_cache_keys:' . $role, $cache_key);
            }
            $permissions = RolePermissions::query()->whereIn('role_id', $roles)->pluck('permission_id');
            $menus = Permission::query()->isMenu()->whereIn('id', $permissions)->get();
            $menus = Permission::makeTree($menus);
            return $menus;
        });
    }

    /**
     * 获取角色下包含的权限关键字
     * @param array $roles
     * @return mixed
     */
    public function permissionKeywords($roles)
    {
        sort($roles);
        $cache_key = 'role_permission_keywords:' . implode('_', $roles);
        return Cache::remember($cache_key, $this->ttl(), function () use ($roles, $cache_key) {
            foreach ($roles as $role) {
                Redis::sadd('role_cache_keys:' . $role, $cache_key);
            }
            $permissions = RolePermissions::query()->whereIn('role_id', $roles)->pluck('permission_id');
            $keywords = Permission::query()->whereIn('id', $permissions)->whereNotNull('keyword')->pluck('keyword')->map(function ($keyword) {
                if ($keyword) {
                    return explode(',', $keyword);
                }
            })->collapse()->toArray();
            return $keywords;
        });
    }

    /**
     * 判断是否拥有权限
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        // XXX 后续可以考虑将 roles 存到 jwt 里或者是 user 模型中
        $roles = $this->roles->pluck('id')->toArray();

        $keywords = $this->permissionKeywords($roles);
        return in_array($permission, $keywords);
    }
}
