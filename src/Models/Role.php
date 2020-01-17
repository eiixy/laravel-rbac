<?php


namespace Eiixy\Rbac\Models;


use Illuminate\Database\Eloquent\Model;
use Sczts\Skeleton\Traits\Models\Random;

class Role extends Model
{
    use Random;

    protected $table = 'rbac_roles';

    protected $guarded = [];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, RolePermissions::class, 'role_id', 'permission_id');
    }

    public function users()
    {
        return $this->belongsToMany(config('rbac.users'), UserRole::class, 'role_id', 'user_id');
    }
}
