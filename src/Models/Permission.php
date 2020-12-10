<?php


namespace Sczts\Rbac\Models;


use Illuminate\Database\Eloquent\Model;
use Sczts\Skeleton\Traits\ORM\HasChildren;

class Permission extends Model
{
    use HasChildren;

    const TYPE_CATALOG = 0;     // 目录
    const TYPE_PAGE = 1;        // 页面
    const TYPE_ELEMENT = 2;     // 页面元素

    protected $table = 'rbac_permissions';

    protected $guarded = [];


    public function scopeIsMenu($query)
    {
        return $query->where(function ($query) {
            return $query->where('type', Permission::TYPE_CATALOG)->orWhere('type', Permission::TYPE_PAGE);
        });
    }


    protected static function boot()
    {
        parent::boot();
        // 删除前清除所有角色下的此权限
        static::deleting(function (Permission $permission) {
            RolePermissions::query()->where('permission', $permission->id)->delete();
        });
    }
}
