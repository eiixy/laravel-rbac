<?php


namespace Eiixy\Rbac\Http\Controllers;

use Eiixy\Rbac\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Sczts\Skeleton\Http\Controllers\Controller;
use Sczts\Skeleton\Http\StatusCode;
use Sczts\Skeleton\Traits\RestFul;

class PermissionController extends Controller
{
    use RestFul;

    protected function getModel(): Builder
    {
        return Permission::query();
    }

    public function list(Request $request)
    {
        $query = static::filter($this->getModel(), $request)->with('children')->withCount('children');
        $data = $query->get();
        return $this->json(StatusCode::SUCCESS, ['data' => $data]);
    }

    public function all()
    {
        $data = $this->getModel()->where('pid',0)->with('children.children.children')->get();
        return $this->json(StatusCode::SUCCESS, ['data' => $data]);
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
