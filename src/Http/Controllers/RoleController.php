<?php


namespace Sczts\Rbac\Http\Controllers;

use Sczts\Rbac\Models\Role;
use Sczts\Rbac\Models\RolePermissions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Routing\Controller;
use Sczts\Skeleton\Traits\RestFul;


class RoleController extends Controller
{
    use RestFul;

    public function show($id)
    {
        $data = $this->getModel()->findOrFail($id);
        $data->permissions = RolePermissions::query()->where('role_id', $id)->pluck('permission_id');
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->addRule());
        $permissions = Arr::pull($data, 'permissions');
        $role = $this->getModel()->create($data);
        if ($role) {
            $role->permissions()->attach($permissions);
            return $this->json(StatusCode::SUCCESS);
        }
        return $this->json(StatusCode::ERROR);
    }

    public function update($id, Request $request)
    {
        $data = $request->validate($this->editRule());
        $permissions = Arr::pull($data, 'permissions');
        $role = $this->getModel()->findOrFail($id);
        $role->update($data);
        if ($role && $role->update($data)) {
            $role->permissions()->detach();
            $role->permissions()->attach($permissions);
            return $this->json(StatusCode::SUCCESS);
        }
        return $this->failed();
    }

    protected function getModel(): Builder
    {
        return Role::query();
    }

    protected function addRule(): array
    {
        return [
            'name' => 'required',
            'description' => 'nullable',
            'permissions' => 'nullable|array',
            'sort' => 'nullable',
        ];
    }

    protected function editRule(): array
    {
        return [
            'name' => 'required',
            'description' => 'nullable',
            'permissions' => 'nullable|array',
            'sort' => 'nullable',
        ];
    }
}
