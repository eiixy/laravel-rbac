<?php


use Eiixy\Rbac\Models\Permission;
use Eiixy\Rbac\Models\Role;
use Eiixy\Rbac\Models\RolePermissions;
use Eiixy\Rbac\Models\UserRole;
use Illuminate\Support\Facades\Cache;

trait RbacUser
{
    public function roles()
    {
        return $this->hasMany(UserRole::class);
    }

    public function getPermissionAttrbute()
    {
        $roles = $this->roles;
        return static::permission($roles);
    }

    public static function permission($roles)
    {
        sort($roles);
        $last_update = Role::query()->whereIn('id', $roles)->max('updated_at');
        $cache_key = implode('-', $roles) . '/' . strtotime($last_update);

        $permission = Cache::remember($cache_key, 60, function () use ($roles) {
            $ids = RolePermissions::query()->whereIn('role_id', $roles)->pluck('permission_id');
            $keywords = Permission::query()->whereIn('id', $ids)->pluck('keyword')->map(function ($keyword) {
                if ($keyword) {
                    return explode(',', $keyword);
                }
            })->collapse()->toArray();

            $menu = Permission::with('children.children')
                ->where('pid', 0)
                ->whereIn('id', $ids)
                ->whereIn('type', [Permission::TYPE_CATALOG, Permission::TYPE_PAGE])
                ->get()->toArray();
            return [
                'permissions' => $keywords,
                'menu' => $menu
            ];
        });

        return $permission;
    }


}
