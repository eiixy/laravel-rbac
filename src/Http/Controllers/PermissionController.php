<?php


namespace Eiixy\Rbac\Http\Controllers;

use Eiixy\Rbac\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Sczts\Skeleton\Http\Controllers\Controller;
use Sczts\Skeleton\Traits\RestFul;

class PermissionController extends Controller
{
    use RestFul;

    protected function getModel(): Builder
    {
        return Permission::query();
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
