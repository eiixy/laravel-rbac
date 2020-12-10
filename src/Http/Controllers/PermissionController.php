<?php


namespace Sczts\Rbac\Http\Controllers;

use Sczts\Rbac\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller;
use Sczts\Skeleton\Traits\RestFul;

class PermissionController extends Controller
{
    use RestFul;

    protected function getModel(): Builder
    {
        return Permission::query();
    }

    public function all()
    {
        $data = Permission::query()->get();
        $data = Permission::makeTree($data);
        return $this->success($data);
    }

    protected function addRule(): array
    {
        return [
            'pid' => 'nullable',
            'name' => 'required',
            'icon' => 'nullable',
            'type' => 'nullable',
            'url' => 'nullable',
            'keyword' => 'nullable',
            'sort' => 'nullable',
        ];
    }

    protected function editRule(): array
    {
        return [
            'pid' => 'nullable',
            'name' => 'required',
            'icon' => 'nullable',
            'type' => 'nullable',
            'url' => 'nullable',
            'keyword' => 'nullable',
            'sort' => 'nullable',
        ];
    }
}
