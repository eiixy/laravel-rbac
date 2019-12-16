<?php


namespace Eiixy\Rbac\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'rbac_roles';

    public function permissions()
    {
        return $this->hasManyThrough(Permission::class,'');
    }
}
