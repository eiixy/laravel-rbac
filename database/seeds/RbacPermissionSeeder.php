<?php

use Sczts\Rbac\Models\Permission;
use Sczts\Skeleton\Traits\SeederTrait;
use Illuminate\Database\Seeder;


class RbacPermissionSeeder extends Seeder
{
    use SeederTrait;
    public function run()
    {
        $this->recursiveCreate(Permission::query(),$this->data());
    }
    private function data(){
        return [
            [
                'name' => '系统管理',
                'children' => [
                    [
                        'name' => '角色管理',
                        'type' => Permission::TYPE_PAGE,
                        'url' => 'role',
                        'keyword' => 'sys_role.list,sys_role.show',
                        'children' => [
                            [
                                'name' => '添加角色',
                                'type' => Permission::TYPE_ELEMENT,
                                'keyword' => 'sys_role.add'
                            ],
                            [
                                'name' => '编辑角色',
                                'type' => Permission::TYPE_ELEMENT,
                                'keyword' => 'sys_role.update'
                            ],
                            [
                                'name' => '删除角色',
                                'type' => Permission::TYPE_ELEMENT,
                                'keyword' => 'sys_role.delete'
                            ],
                        ]
                    ],
                    [
                        'name' => '权限管理',
                        'type' => Permission::TYPE_PAGE,
                        'url' => 'permission',
                        'keyword' => 'sys_permission.list,sys_permission.show',
                        'children' => [
                            [
                                'name' => '添加权限',
                                'type' => Permission::TYPE_ELEMENT,
                                'keyword' => 'sys_permission.add'
                            ],
                            [
                                'name' => '编辑权限',
                                'type' => Permission::TYPE_ELEMENT,
                                'keyword' => 'sys_permission.update'
                            ],
                            [
                                'name' => '删除权限',
                                'type' => Permission::TYPE_ELEMENT,
                                'keyword' => 'sys_permission.delete'
                            ],
                        ]
                    ]
                ]
            ]
        ];
    }
}
