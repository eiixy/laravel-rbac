<?php

use Eiixy\Rbac\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;


class RbacPermissionSeeder extends Seeder
{
    public function run()
    {
        //
        $data = $this->data();
        $this->create(Permission::query(),$data);
    }

    public function create($model,$data,$pid=0)
    {
        foreach ($data as $item){
            $children = Arr::pull($item,'children');
            $item['pid'] = $pid;
            $parent = $model->create($item);
            if (!empty($children)){
                $this->create($model,$children,$parent->id);
            }
        }
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
                        'keyword' => 'sys_role:list',
                        'children' => [
                            [
                                'name' => '添加角色',
                                'type' => Permission::TYPE_BUTTON,
                                'keyword' => 'sys_role:add'
                            ],
                            [
                                'name' => '编辑角色',
                                'type' => Permission::TYPE_BUTTON,
                                'keyword' => 'sys_role:update'
                            ],
                            [
                                'name' => '删除角色',
                                'type' => Permission::TYPE_BUTTON,
                                'keyword' => 'sys_role:delete'
                            ],
                        ]
                    ],
                    [
                        'name' => '权限管理',
                        'type' => Permission::TYPE_PAGE,
                        'url' => 'role',
                        'keyword' => 'sys_permission:list',
                        'children' => [
                            [
                                'name' => '添加权限',
                                'type' => Permission::TYPE_BUTTON,
                                'keyword' => 'sys_permission:add'
                            ],
                            [
                                'name' => '编辑权限',
                                'type' => Permission::TYPE_BUTTON,
                                'keyword' => 'sys_permission:update'
                            ],
                            [
                                'name' => '删除权限',
                                'type' => Permission::TYPE_BUTTON,
                                'keyword' => 'sys_permission:delete'
                            ],
                        ]
                    ]
                ]
            ]
        ];
    }
}
