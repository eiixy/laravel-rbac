<?php


namespace Eiixy\Rbac\Models;


use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    const TYPE_CATALOG = 0;     // 目录
    const TYPE_PAGE = 1;        // 页面
    const TYPE_BUTTON = 2;      // 按钮

    protected $table = 'rbac_permissions';

    protected $guarded = [];

    public function children()
    {
        return $this->hasMany(Permission::class,'pid');
    }
}
