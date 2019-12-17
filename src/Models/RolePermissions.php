<?php


namespace Eiixy\Rbac\Models;


use Illuminate\Database\Eloquent\Model;

class RolePermissions extends Model
{
    protected $table = 'rbac_role_permissions';

    protected $touches = ['role'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
