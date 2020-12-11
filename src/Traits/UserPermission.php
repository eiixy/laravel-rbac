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
    private function ttl()
    {
        return config('rbac.cache_ttl', 43200);
    }

    /**
     * 获取角色缓存键名
     * @param $role_id
     * @return string
     */
    private function getRoleCacheKey($role_id)
    {
        return 'role_cache_keys:' . $role_id;
    }

    /**
     * 获取权限缓存键名
     * @param $roles
     * @param $type
     * @return string
     */
    private function getPermissionCacheKey($roles, $type)
    {
        sort($roles);
        return 'role_permission_' . $type . ':' . implode('_', $roles);
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
     * @param $roles
     * @return mixed
     */
    public function permissionMenus($roles)
    {
        $cache_key = $this->getPermissionCacheKey($roles, 'menus');

        return Cache::remember($cache_key, $this->ttl(), function () use ($roles, $cache_key) {
            foreach ($roles as $role_id) {
                Redis::sadd($this->getRoleCacheKey($role_id), $cache_key);
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
        $cache_key = $this->getPermissionCacheKey($roles, 'keywords');

        return Cache::remember($cache_key, $this->ttl(), function () use ($roles, $cache_key) {
            foreach ($roles as $role_id) {
                Redis::sadd($this->getRoleCacheKey($role_id), $cache_key);
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
