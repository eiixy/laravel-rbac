<?php


namespace Eiixy\Rbac\Http\Controllers;

use Eiixy\Rbac\Models\Permission;
use Illuminate\Http\Request;
use Sczts\Skeleton\Http\Controllers\Controller;
use Sczts\Skeleton\StatusCode;

class PermissionController extends Controller
{
    /**
     * 权限列表
     * @param Request $request
     */
    public function list(Request $request)
    {
        dd(2311);
    }

    public function store()
    {
        $permission = $this->validate([
            'pid' => 'nullable',
            'name' => 'required',
            'icon' => 'nullable',
            'type' => 'nullable',
            'url' => 'nullable',
            'keyword' => 'nullable',
            'sort' => 'nullable',
        ]);
        $result = Permission::query()->create($permission);
        if ($result){
            return $this->json(StatusCode::SUCCESS,['data'=>$result]);
        }
        return $this->json(StatusCode::ERROR,['result'=>$result]);
    }

    public function update($id,Request $request)
    {
        
    }


}
