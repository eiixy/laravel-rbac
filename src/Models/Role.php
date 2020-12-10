<?php


namespace Sczts\Rbac\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $table = 'rbac_roles';

    protected $guarded = [];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, RolePermissions::class, 'role_id', 'permission_id');
    }

    public function users()
    {
        return $this->belongsToMany(config('rbac.model'), UserRole::class, 'role_id', 'user_id');
    }

    protected static function boot()
    {
        parent::boot();
        // 删除前清除所有用户下的此角色
        static::deleting(function (Role $role){
            UserRole::query()->where('role_id',$role->id)->delete();
        });
    }
}
