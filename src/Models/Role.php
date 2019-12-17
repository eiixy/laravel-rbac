<?php


namespace Eiixy\Rbac\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'rbac_roles';

    protected $guarded = [];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, RolePermissions::class, 'role_id', 'permission_id');
    }
}
