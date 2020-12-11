<?php


namespace Sczts\Rbac\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Sczts\Skeleton\Traits\JsonResponse;

class CommonController extends Controller
{
    use JsonResponse;

    //
    public function routes()
    {
        $routes = app()->routes->getRoutes();
        $list = [];
        foreach ($routes as $route) {
            if (key_exists('middleware', $route->action)) {
                $middleware = $route->action['middleware'];
                foreach (Arr::wrap($middleware) as $item) {
                    if (Str::startsWith($item, 'rbac.check:')) {
                        $list[] = [
                            'uri' => $route->uri,
                            'methods' => $route->methods,
                            'label' => $route->action['description'] ?? explode(':', $item)[1],
                            'value' => explode(':', $item)[1]
                        ];
                    }
                }
            }
        }
        return $this->success($list);
    }
}
