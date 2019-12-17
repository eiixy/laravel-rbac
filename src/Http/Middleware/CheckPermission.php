<?php


namespace Eiixy\Rbac\Http\Middleware;


use Closure;
use Sczts\Skeleton\Http\StatusCode;

class CheckPermission
{
    /**
     * 检查用户权限
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $user = auth('admin')->user();
        if ($user == null) {
            return response()->json(StatusCode::ERROR, 401);
        }

        if ($user->hasPermission($permission)) {
            return $next($request);
        }
        return response()->json(StatusCode::NO_PERMISSION);
    }
}
