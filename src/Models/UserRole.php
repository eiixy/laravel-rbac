<?php


namespace Eiixy\Rbac\Models;


use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'rbac_user_roles';

    public $timestamps = false;
}
