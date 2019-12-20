<?php

use Eiixy\Rbac\Models\Role;
use Eiixy\Rbac\Models\User;
use Eiixy\Rbac\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;


class RbacRoleSeeder extends Seeder
{
    public function run()
    {
        //
        $user = User::create([
            'username' => 'admin',
            'email' => 'admin@qq.com',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'type' => User::TYPE_SUPER_ADMIN,
            'status' => User::STATUS_NORMAL,
        ]);

        $roles = $this->data();
        foreach ($roles as $item) {
            $permissions = Arr::pull($item, 'permissions');
            $role = Role::create($item);
            $role->permissions()->attach($permissions);
        }

        UserRole::query()->create([
            'user_id' => $user->id,
            'role_id' => 1
        ]);
    }

    private function data()
    {
        return [
            [
                'name' => '管理员',
                'description' => '拥有所有权限',
                'permissions' => [1,2,3,4,5,6,7,8,9]
            ],
            [
                'name' => '角色1',
                'description' => '查看权限列表，管理角色权限',
                'permissions' => [1,2,3,4,5,6]
            ],
            [
                'name' => '角色2',
                'description' => '查看权限列表，查看角色列表',
                'permissions' => [1,2,6]
            ]
        ];
    }
}
