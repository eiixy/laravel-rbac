<?php


namespace Eiixy\Rbac\Http\Controllers;

use Eiixy\Rbac\Models\Role;
use Eiixy\Rbac\Models\RolePermissions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Sczts\Skeleton\Http\Controllers\Controller;
use Sczts\Skeleton\Traits\RestFul;
use Sczts\Skeleton\Http\StatusCode;


class RoleController extends Controller
{
    use RestFul;

    public function show($id)
    {
        $data = $this->getModel()->findOrFail($id);
        $data->permissions = RolePermissions::where('role_id',$id)->pluck('permission_id');
        return $this->json(StatusCode::SUCCESS, ['data' => $data]);
    }

    public function store()
    {
        $data = $this->validator($this->addRule());
        $permissions = Arr::pull($data, 'permissions');
        $role = $this->getModel()->create($data);
        if ($role) {
            $role->permissions()->attach($permissions);
            return $this->json(StatusCode::SUCCESS);
        }
        return $this->json(StatusCode::ERROR);
    }

    public function update($id)
    {
        $data = $this->validator($this->editRule());
        $permissions = Arr::pull($data, 'permissions');
        $role = $this->getModel()->findOrFail($id);
        $role->update($data);
        if ($role && $role->update($data)) {
            $role->permissions()->detach();
            $role->permissions()->attach($permissions);
            return $this->json(StatusCode::SUCCESS);
        }
        return $this->json(StatusCode::ERROR);
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
