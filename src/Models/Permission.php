<?php


namespace Eiixy\Rbac\Models;


use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    const TYPE_CATALOG = 0;     // 目录
    const TYPE_PAGE = 1;        // 页面
    const TYPE_ELEMENT = 2;     // 页面元素

    protected $table = 'rbac_permissions';

    protected $guarded = [];

    public function children()
    {
        return $this->hasMany(Permission::class,'pid');
    }

    public function scopeIsMenu($query)
    {
        return $query->whereIn('type', [Permission::TYPE_CATALOG, Permission::TYPE_PAGE]);
    }

    public function scopeInRoles($query,$roles)
    {
        return $query->whereIn('id', $roles);
    }

    protected static function boot()
    {
        parent::boot();
        // 删除前清除所有角色下的此权限
        static::deleting(function (Permission $permission){
            RolePermissions::query()->where('permission',$permission->id)->delete();
        });
    }
}
