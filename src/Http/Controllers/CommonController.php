<?php

namespace Eiixy\Rbac\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Sczts\Skeleton\Http\StatusCode;
use Sczts\Skeleton\Http\Controllers\Controller;

class CommonController extends Controller
{
    //
    public function routes()
    {
        $routes = app()->routes->getRoutes();
        $list = [];
        foreach ($routes as $route) {
            if (key_exists('middleware',$route->action)) {
                $middleware = $route->action['middleware'];
                foreach (Arr::wrap($middleware) as $item) {
                    if (Str::startsWith($item, 'rbac.check:')) {
                        $list[] = [
                            'uri' => $route->uri,
                            'methods' => $route->methods,
                            'label' => explode(':', $item)[1],
                            'value' => explode(':', $item)[1]
                        ];
                    }
                }
            }
        }
        return $this->json(StatusCode::SUCCESS, ['data' => $list]);
    }
}
