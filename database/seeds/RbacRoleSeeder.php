<?php

use Sczts\Rbac\Models\Role;
use Sczts\Rbac\Models\Permission;
use Illuminate\Database\Seeder;


class RbacRoleSeeder extends Seeder
{
    public function run()
    {
        // 创建角色，关联所有权限
        $permissions = Permission::query()->pluck('id')->toArray();
        $role = Role::query()->firstOrCreate([
            'name' => '超级管理员',
            'description' => '拥有所有权限'
        ]);
        $role->permissions()->attach($permissions);
    }
}
